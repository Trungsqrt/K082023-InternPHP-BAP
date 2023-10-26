<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use function Laravel\Prompts\error;

trait APIResponsesTrait
{
    /**
     * Respond with success.
     *
     * @param mixed $data The data to be included in the response.
     * @param int $statusCode The status code of the response (default: 200).
     * @throws Some_Exception_Class Description of exception.
     * @return JsonResponse The JSON response containing the status, errors, and result.
     */
    protected function respondSuccess($data, $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => $statusCode,
            'errors' => [],
            'result' => $data,
        ], $statusCode);
    }

    /**
     * Responds with an error message and status code.
     *
     * @param mixed $error The error message or an array containing 'code' and 'message' keys.
     * @param int $statusCode The HTTP status code. Default is 400 (Bad Request).
     * @return JsonResponse The JSON response containing the error message and status code.
     */
    protected function respondError($error, $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $statusCode = isset($error['code']) ? $error['code'] : $statusCode;
        $errorCode = isset($error['code']) && intval($error['code'] / 100) === 4 ? 400 : $statusCode;

        return response()->json([
            'status' => $statusCode,
            'errors' => [
                'code' => $errorCode,
                'message' => $error['message'],
            ],
            'result' => null,
        ], $statusCode);
    }
}
