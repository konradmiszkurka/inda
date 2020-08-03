<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\CurrencyLocale\Interfaces;

use App\Modules\Product\Domain\CurrencyLocale\Entity\CurrencyLocaleEntity;

interface CurrencyLocaleRepositoryInterface
{
    public function findByLocale(string $locale): CurrencyLocaleEntity;
}