<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\Product\Interfaces;

use App\Lib\Domain\Collection\CollectionInterface;
use App\Lib\Domain\Criteria\CriteriaInterface;
use App\Lib\Domain\Pagination\PaginationInterface;
use App\Lib\Domain\Sorting\SortingInterface;
use App\Modules\Product\Domain\Product\Entity\ProductEntity;

interface ProductRepositoryInterface
{
    public function find(int $id): ProductEntity;

    public function findAll(
        ?CriteriaInterface $criteria = null,
        ?PaginationInterface $pagination = null,
        ?SortingInterface $sorting = null
    ): CollectionInterface;
}