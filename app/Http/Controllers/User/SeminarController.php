<?php

namespace App\Http\Controllers\User;

use App\Helpers\CheckAppError;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplySeminarRequest;
use App\Http\Requests\User\ApplySeminarRequest as UserApplySeminarRequest;
use App\Http\Requests\User\GetDetailSeminarRequest;
use Illuminate\Http\Request;

class SeminarController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Retrieves the list of seminars for the user.
     *
     * @throws Some_Exception_Class if there is an error retrieving the seminars
     * @return Some_Return_Value an array containing the list of seminars
     */
    public function index()
    {
        $seminars = $this->userService->getSeminars();

        if (CheckAppError::isAppError($seminars)) {
            return $this->respondError($seminars->getErrorData());
        }

        return $this->respondSuccess([
            'seminars' => $seminars
        ]);
    }

    /**
     * Retrieves and displays the details of a seminar.
     *
     * @param GetDetailSeminarRequest $request The request object containing the seminar ID, member ID, and user agent.
     *
     * @throws App error if there is an description of exception
     *
     * @return Some_Return_Value The response object containing the details of the seminar.
     */
    public function show(GetDetailSeminarRequest $request)
    {
        $dataValidated = $request->validated();

        $member_id = $request->input('member_id');
        $seminar_id = $dataValidated['seminar_id'];
        $user_agent = $dataValidated['user_agent'];

        $seminar = $this->userService->getDetailSeminar($seminar_id, $member_id, $user_agent);

        if (CheckAppError::isAppError($seminar)) {
            return $this->respondError($seminar->getErrorData());
        }

        return $this->respondSuccess($seminar);
    }

    /**
     * Applies for a seminar.
     *
     * @param UserApplySeminarRequest $request the request object containing the validated data
     * @throws App error if there is an description of exception
     * @return Seminar_application information
     */
    public function apply(UserApplySeminarRequest $request)
    {
        $dataValidated = $request->validated();

        $seminar_id = $dataValidated['seminar_id'];
        $member_id = $dataValidated['member_id'];
        $seminar_application_category = $dataValidated['seminar_application_category'];

        $seminar = $this->userService->applySeminar($seminar_id, $member_id, $seminar_application_category);

        if (CheckAppError::isAppError($seminar)) {
            return $this->respondError($seminar->getErrorData());
        }

        return $this->respondSuccess($seminar);
    }
}
