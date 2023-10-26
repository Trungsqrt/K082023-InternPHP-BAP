<?php

namespace App\Utils;

class AppErrorData
{
    private $code;

    private $message;

    private $data;

    public function __construct($message, int $code = 0, mixed $data = null)
    {
        if (is_array($message)) {
            $this->message = $message['message'] ?? null;
            $this->code = $message['code'] ?? 0;
        } else {
            $this->message = $message;
            $this->code = $code;
        }

        $this->data = $data;
    }

    /**
     * Retrieves the code.
     *
     * @return int The code.
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Retrieves the message.
     *
     * @return string The message.
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Retrieves the data.
     *
     * @return string The data.
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @return array<string>
     */
    public function getErrorData(): array
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}
