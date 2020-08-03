<?php

declare(strict_types=1);

namespace App\Modules\Product\UI\Admin\Request;

use App\Modules\Product\Domain\Price\Interfaces\DataInterface;

final class FormPriceData implements DataInterface
{
    /**
     * @var string
     */
    private $currency;
    /**
     * @var string
     */
    private $value;

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return FormPriceData
     */
    public function setCurrency(string $currency): FormPriceData
    {
        $this->currency = $currency;
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
     * @return FormPriceData
     */
    public function setValue(string $value): FormPriceData
    {
        $this->value = $value;
        return $this;
    }


}