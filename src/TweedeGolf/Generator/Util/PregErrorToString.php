<?php

namespace TweedeGolf\Generator\Util;

class PregErrorToString
{
    /**
     * Convert a PREG error message id to its string equivalent.
     * @param int $error
     * @return string
     * @throws \BadMethodCallException
     */
    public static function getString($error)
    {
        if (!is_int($error)) {
            throw new \BadMethodCallException("Invalid type, expected integer, got {$error}.");
        }

        switch ($error) {
            case PREG_NO_ERROR: return 'PREG_NO_ERROR';
            case PREG_INTERNAL_ERROR: return 'PREG_INTERNAL_ERROR';
            case PREG_BACKTRACK_LIMIT_ERROR: return 'PREG_BACKTRACK_LIMIT_ERROR';
            case PREG_BAD_UTF8_ERROR: return 'PREG_BAD_UTF8_ERROR';
            case PREG_RECURSION_LIMIT_ERROR: return 'PREG_RECURSION_LIMIT_ERROR';
            case PREG_BAD_UTF8_OFFSET_ERROR: return 'PREG_BAD_UTF8_OFFSET_ERROR';
            default:
                throw new \BadMethodCallException("Tried to convert unknown PREG error {$error} to string");
        }
    }
}
