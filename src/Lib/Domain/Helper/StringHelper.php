<?php

declare(strict_types=1);

namespace App\Lib\Domain\Helper;

use Exception;

class StringHelper
{
    private const LENGTH = 32;

    public static function generateRandomString(?int $length = null): string
    {
        if ($length === null) {
            $length = static::LENGTH;
        }

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function uniqidReal(?int $length = null): string
    {
        if ($length === null) {
            $length = static::LENGTH;
        }

        if (function_exists("random_bytes")) {
            $bytes = random_bytes((int)ceil($length / 2));
        } else {
            throw new Exception('no cryptographically secure random function available');
        }

        return substr(bin2hex($bytes), 0, $length);
    }
}