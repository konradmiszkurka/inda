<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\Product\Interfaces;

use App\Modules\Product\Domain\Product\Entity\ProductEntity;

interface ProductPersistenceInterface
{
    public function persist(ProductEntity $entity): void;

    public function flush(): void;
}