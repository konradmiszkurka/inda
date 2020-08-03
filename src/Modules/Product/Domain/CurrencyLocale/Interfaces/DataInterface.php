<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\CurrencyLocale\Interfaces;

interface DataInterface
{
    public function getLocale(): ?string;

    public function getValue(): ?string;
}