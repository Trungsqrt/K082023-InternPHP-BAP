<?php

namespace App\Helpers;

use App\Utils\AppError;

class CheckAppError
{
    /**
     * Check if the given object is an instance of the AppError class.
     *
     * @param mixed $object The object to check.
     * @return bool Returns true if the object is an instance of AppError, false otherwise.
     */
    public static function isAppError($object): bool
    {
        return is_a($object, AppError::class);
    }
}
