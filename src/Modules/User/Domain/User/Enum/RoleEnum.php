<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\User\Enum;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class RoleEnum extends AbstractEnumType
{
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';
    const ROLE_SUPER_ADMIN = 'super_admin';

    protected static $choices = [
        self::ROLE_USER => 'User',
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_SUPER_ADMIN => 'Super admin',
    ];

    protected static $realRole = [
        self::ROLE_USER => ['ROLE_USER'],
        self::ROLE_ADMIN => ['ROLE_ADMIN'],
        self::ROLE_SUPER_ADMIN => ['ROLE_SUPER_ADMIN'],
    ];

    public static function getRealRole(string $roleName): array
    {
        return static::$realRole[$roleName];
    }

    public static function getConst(array $role): string
    {
        $result = static::ROLE_USER;

        if (in_array('ROLE_USER', $role, true)) {
            $result = static::ROLE_USER;
        }
        if (in_array('ROLE_ADMIN', $role, true)) {
            $result = static::ROLE_ADMIN;
        }
        if (in_array('ROLE_SUPER_ADMIN', $role, true)) {
            $result = static::ROLE_SUPER_ADMIN;
        }

        return $result;
    }

    public static function getConstName(array $role): string
    {
        return static::$choices[static::getConst($role)];
    }
}