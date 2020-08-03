<?php
declare(strict_types=1);

namespace App\Modules\Attachment\Domain\Photo\Interfaces;

use App\Lib\Domain\Collection\CollectionInterface;
use App\Lib\Domain\Criteria\CriteriaInterface;
use App\Lib\Domain\Pagination\PaginationInterface;
use App\Lib\Domain\Sorting\SortingInterface;
use App\Modules\Attachment\Domain\Photo\Entity\PhotoEntity;

interface PhotoRepositoryInterface
{
    public function findAll(
        ?CriteriaInterface $criteria = null,
        ?PaginationInterface $pagination = null,
        ?SortingInterface $sorting = null
    ): CollectionInterface;

    public function find(int $id): ?PhotoEntity;
}