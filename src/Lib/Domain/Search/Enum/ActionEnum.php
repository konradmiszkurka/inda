<?php
declare(strict_types=1);

namespace App\Lib\Domain\Search\Enum;

class ActionEnum
{
    const SHOW = 'show';
    const EDIT = 'edit';
    const REMOVE = 'remove';

    protected static $icon = [
        self::SHOW => 'eye',
        self::EDIT => 'edit',
        self::REMOVE => 'delete',
    ];

    public static function getIconReadableValue(string $value): string
    {
        return static::$icon[$value];
    }
}
