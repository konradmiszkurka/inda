<?php
declare(strict_types=1);

namespace App\Modules\Attachment\Domain\File\Enum;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class CategoryEnum extends AbstractEnumType
{
    public const AVATAR = 'avatar';

    protected static $choices = [
        self::AVATAR => 'Avatar',
    ];
}
