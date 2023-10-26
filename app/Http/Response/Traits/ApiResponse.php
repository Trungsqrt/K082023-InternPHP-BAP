<?php

namespace App\Http\Response\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * Generate a JSON response with a success status code.
     *
     * @param mixed $data The data to include in the response.
     * @param int $statusCode The HTTP status code to use for the response. (optional, default: Response::HTTP_OK)
     * @throws None
     * @return JsonResponse The JSON response object.
     */
    protected function respondSuccess($data, $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'status' => $statusCode,
            'error' => [],
            'result' => $data,
        ], $statusCode);
    }

    /**
     * Responds with an error message in JSON format.
     *
     * @param mixed $error The error message or array containing error details.
     * @param int $statusCode The HTTP status code for the response. Defaults to 422 (Unprocessable Entity).
     * @return JsonResponse The JSON response containing the error message.
     */
    protected function respondError($error, $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY): JsonResponse
    {
        $statusCode = isset($error['code']) ? $error['code'] : $statusCode;
        $errorCode = isset($error['code']) && intval($error['code'] / 100) === 4 ? 400 : $statusCode;
        return response()->json([
            'status' => $statusCode,
            'error' => [
                'code' => $errorCode,
                'message' => $error['message'],
            ],
            'result' => null,
        ], $statusCode);
    }
}
