<?php

namespace App\Exceptions;

use App\Http\Response\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AppException extends Exception
{
    use ApiResponse;

    public function __construct($message, int $code = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        $this->message = $message;
        $this->code = $code;

        parent::__construct($this->message, $this->code);
    }

    /**
     * Renders the response as a JSON.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return $this->respondError($this->getErrorData(), $this->code);
    }

    /**
     * @return array<string>
     */
    public function getErrorData(): array
    {
        return config("error.$this->message", [
            'code' => $this->code,
            'message' => $this->message,
        ]);
    }
}
