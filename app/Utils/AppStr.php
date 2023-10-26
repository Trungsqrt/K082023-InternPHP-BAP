<?php

namespace App\Utils;

use Illuminate\Support\Str;

class AppStr
{
    /**
     * Returns the error name based on the given text.
     *
     * @param mixed $txt The text to generate the error name from.
     * @return string The generated error name.
     */
    public static function getErrorName($txt): string
    {
        $txt = str_replace('.', '_', $txt);
        $txt = trim($txt, '_');

        return Str::snake($txt);
    }

    /**
     * A function that takes in a text and returns a sanitized version of it.
     *
     * @param mixed $txt The text to be sanitized.
     * @return string The sanitized version of the text.
     */
    public static function sanityText($txt): string
    {
        return strip_tags($txt);
    }
}
