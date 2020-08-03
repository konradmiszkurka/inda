<?php
declare(strict_types=1);

namespace App\Lib\Domain\Search\DTO;

use DateTime;
use App\Lib\Domain\Search\Enum\DateColorEnum;
use JMS\Serializer\Annotation as Serializer;

final class Date
{
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $color;
    /**
     * @var DateTime
     * @Serializer\Type("DateTime<'Y-m-d\TH:i:s.uT'>")
     * @Serializer\SerializedName("dateTime")
     */
    private $dateTime;

    public function getColor(): string
    {
        return $this->color ?? DateColorEnum::DEFAULT;
    }

    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }
}
