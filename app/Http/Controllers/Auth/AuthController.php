<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\CheckAppError;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    protected $authService;

    // change to authService
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Logs in a user and returns a JSON response.
     *
     * @param LoginRequest $request The login request object.
     * @throws Authentication Exception.
     * @return JsonResponse The JSON response containing the access token, token type, and user ID.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $guard = $request->is('api/admin/login') ? 'adminapi' : 'userapi';

        $data = $request->safe()->only(['email', 'password']);
        $tokenResult = $this->authService->login($data, $guard);

        if (CheckAppError::isAppError($tokenResult)) {
            return $this->respondError($tokenResult->getErrorData());
        }

        $user_id = $guard == 'userapi' ? auth()->guard($guard)->user()->MEMBER_ID : null;

        return $this->respondSuccess([
            'access_token' => $tokenResult,
            'token_type' => 'bearer',
            'user_id' => $user_id
        ]);
    }

    /**
     * Registers a user.
     *
     * @param RegisterRequest $request The request object containing the user's registration data.
     * @return mixed The response data.
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->safe()->only(['first_name', 'last_name', 'email', 'password']);

        $tokenResult = $this->authService->register($data);
        if (CheckAppError::isAppError($tokenResult)) {
            return $this->respondError($tokenResult->getErrorData());
        }

        return $this->respondSuccess([
            'access_token' => $tokenResult,
            'token_type' => 'bearer',
        ]);
    }

    /**
     * Refreshes the access token for the specified user or admin.
     *
     * @param Request $request The HTTP request object.
     * @return JsonResponse The JSON response containing the new access token and token type.
     */
    public function refresh(Request $request): JsonResponse
    {
        $guard = $request->is('api/admin/refresh') ? 'adminapi' : 'userapi';

        $newToken = auth()->guard($guard)->refresh();

        return $this->respondSuccess([
            'access_token' => $newToken,
            'token_type' => 'bearer',
        ]);
    }
}
