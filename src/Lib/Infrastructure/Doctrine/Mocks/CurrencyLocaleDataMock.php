<?php
declare(strict_types=1);

namespace App\Lib\Infrastructure\Doctrine\Mocks;

use App\Modules\Product\Domain\CurrencyLocale\Interfaces\DataInterface;
use App\Modules\User\Domain\User\Enum\RoleEnum;
use App\Modules\User\Domain\User\Interfaces\UserDataInterface;

final class CurrencyLocaleDataMock implements DataInterface
{
    /**
     * @var string
     */
    private $locale;

    /**
     * @var string
     */
    private $value;

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return CurrencyLocaleDataMock
     */
    public function setLocale(string $locale): CurrencyLocaleDataMock
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return CurrencyLocaleDataMock
     */
    public function setValue(string $value)
    {
        $this->value = $value;
        return $this;
    }
}