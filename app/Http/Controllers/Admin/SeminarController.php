<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CheckAppError;
use App\Models\Seminar;
use Illuminate\Http\Request;
use App\Services\AdminService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteSeminarRequest;
use App\Http\Requests\UpdateSeminarRequest;
use App\Http\Requests\Admin\StoreSeminarRequest as AdminStoreSeminarRequest;

class SeminarController extends Controller
{

    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }


    /**
     * Retrieves a list of seminars based on search keyword and category.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object.
     * @throws Some_Exception_Class Description of exception
     * @return \Illuminate\Http\JsonResponse The JSON response containing the seminar list.
     */
    public function index(Request $request)
    {
        $keyword = $request->input('search');
        $category = $request->input('category');

        $seminars = $this->adminService->getSeminars($keyword, $category);

        if (CheckAppError::isAppError($seminars))
            return $this->respondError($seminars->getErrorData());

        return $this->respondSuccess([
            'seminars' => $seminars,
        ]);
    }


    /**
     * Store a new seminar.
     *
     * @param AdminStoreSeminarRequest $request The validated seminar request.
     * @throws Exception if there is an error creating the seminar
     * @return Reponse of datas created
     */
    public function store(AdminStoreSeminarRequest $request)
    {
        $validatedData = $request->validated();

        $datas = $this->adminService->createSeminar($validatedData);
        if (CheckAppError::isAppError($datas))
            return $this->respondError($datas->getErrorData());

        return $this->respondSuccess([
            'seminar' => $datas,
        ]);
    }

    /**
     * Deletes a seminar.
     *
     * @param DeleteSeminarRequest $request the request to delete the seminar
     * @throws Some_Exception_Class description of exception
     * @return Message of success
     */
    public function destroy(DeleteSeminarRequest $request)
    {
        $validatedData = $request->validated();

        $result = $this->adminService->deleteSeminar($validatedData);

        if (CheckAppError::isAppError($result))
            return $this->respondError($result->getErrorData());

        return $this->respondSuccess($result);
    }
}
