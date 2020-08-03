<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\Price\Interfaces;

use App\Modules\Product\Domain\Price\Entity\PriceEntity;

interface PricePersistenceInterface
{
    public function persist(PriceEntity $entity): void;

    public function flush(): void;
}