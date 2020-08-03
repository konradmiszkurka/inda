<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\Product\Interfaces;

use App\Modules\Product\Domain\Product\Entity\ProductEntity;

interface ProductRepositoryInterface
{
    public function find(int $id): ProductEntity;
}