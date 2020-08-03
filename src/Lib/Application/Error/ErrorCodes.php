<?php
declare(strict_types=1);

namespace App\Lib\Application\Error;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class ErrorCodes
{
    public const VIOLATION_MAP = [
        Length::TOO_LONG_ERROR => '1001',
        Length::TOO_SHORT_ERROR => '1002',
        Length::INVALID_CHARACTERS_ERROR => '1003',
        NotBlank::IS_BLANK_ERROR => '1004',
        NotNull::IS_NULL_ERROR => '1005',
    ];

    // User
    public const USER_ALREADY_EXISTS = '0021';
    public const USER_EMAIL_OCCUPIED = '0022';
    public const USER_NOT_FOUND = '0023';
    public const USER_PASSWORD_DONT_MATCH = '0024';
    public const USER_PASSWORD_OLD_INCORRECT = '0025';
}
