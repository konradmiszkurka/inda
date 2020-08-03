<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\CurrencyLocale\Interfaces;

use App\Modules\Product\Domain\CurrencyLocale\Entity\CurrencyLocaleEntity;

interface CurrencyLocalePersistenceInterface
{
    public function persist(CurrencyLocaleEntity $entity): void;

    public function flush(): void;
}