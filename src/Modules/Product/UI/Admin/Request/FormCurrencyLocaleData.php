<?php

declare(strict_types=1);

namespace App\Modules\Product\UI\Admin\Request;

use App\Modules\Product\Domain\Price\Entity\PriceEntity;
use App\Modules\Product\Domain\CurrencyLocale\Interfaces\DataInterface;

final class FormCurrencyLocaleData implements DataInterface
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
     * @return FormCurrencyLocaleData
     */
    public function setLocale(string $locale): FormCurrencyLocaleData
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
     * @return FormCurrencyLocaleData
     */
    public function setValue(string $value): FormCurrencyLocaleData
    {
        $this->value = $value;
        return $this;
    }

}