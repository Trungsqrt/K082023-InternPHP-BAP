<?php

namespace App\Services;

use Throwable;
use App\Models\User;
use App\Utils\AppError;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\User\SendMailLoginSuccess;

class AuthService
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Logs in a user using the given data and guard.
     *
     * @param array $data The data needed for the login process.
     *                   It should contain the 'email' and 'password' fields.
     * @param string $guard The name of the guard to use for authentication.
     *                      Valid values are 'adminapi' or any other guard name.
     * @throws AppError If the user does not exist.
     * @return AppError|string The authentication token if the login is successful,
     *                         or an AppError object if the user does not exist.
     */
    public function login(array $data, string $guard): AppError|string
    {
        $token = null;

        if ($guard == 'adminapi') {
            $token = auth()->guard($guard)->attempt(
                [
                    'EMPLOYEE_MAIL_ADDRESS' => $data['email'],
                    'password' => $data['password']
                ]
            );
        } else {
            $token = auth()->guard($guard)->attempt(
                [
                    'MEMBER_MAIL_ADDRESS' => $data['email'],
                    'password' => $data['password']
                ]
            );
        }

        if (!$token) {
            return new AppError(config('error.user_does_not_exist'));
        } else {
            return $token;
        }
    }

    /**
     * Registers a new user.
     *
     * @param array $data The user data including 'first_name', 'last_name', 'email', and 'password'.
     * @throws \Exception If an error occurs during the registration process.
     * @return AppError|string Returns an instance of AppError if there is an error, or a string token if the registration is successful.
     */
    public function register(array $data): AppError|string
    {
        try {
            DB::beginTransaction();

            $token = null;

            if ($this->user->select('MEMBER_MAIL_ADDRESS')->where('MEMBER_MAIL_ADDRESS', $data['email'])->exists()) {
                return new AppError(config('error.email_exist'));
            }

            if ($this->checkPassword($data['password']) == false) {
                return new AppError(config('error.field_restricted.password_field_restricted'));
            }

            $newUser = $this->user->create([
                'MEMBER_FIRST_NAME' => $data['first_name'],
                'MEMBER_FIRST_NAME_KANA' => $data['last_name'],
                'MEMBER_MAIL_ADDRESS' => $data['email'],
                'PASSWORD' => Hash::make($data['password']),
            ]);

            $this->user->where('MEMBER_ID', $newUser->MEMBER_ID)->update([
                'CREATE_PERSON_ID' => $newUser->MEMBER_ID
            ]);

            $token = $this->login([
                'email' => $data['email'],
                'password' => $data['password'],
            ], 'userapi');

            DB::commit();

            return $token;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Checks if a password meets the specified criteria.
     *
     * @param string $password The password to be checked.
     * @return bool Returns true if the password meets the criteria, false otherwise.
     */
    private function checkPassword(string $password): bool
    {
        $regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&#]{8,}$/';

        if (preg_match($regex, base64_decode($password)))
            return true;
        return false;
    }

    /**
     * Sends an email to the specified user.
     *
     * @param mixed $user The user object to send the email to.
     * @throws Throwable If an error occurs while sending the email.
     * @return void
     */
    public function sendEmail($user): void
    {
        $dataSend = [
            'title' => 'Test',
            'body' => 'Login Success',
        ];

        try {
            Mail::to($user->email)->send(new SendMailLoginSuccess($dataSend));
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());
        }
    }
}
