<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\Price\Interfaces;

use App\Modules\Product\Domain\Price\Entity\PriceEntity;

interface PriceRepositoryInterface
{
    public function find(int $id): PriceEntity;
}