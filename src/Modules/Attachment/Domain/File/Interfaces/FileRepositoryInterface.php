<?php
declare(strict_types=1);

namespace App\Modules\Attachment\Domain\File\Interfaces;

use App\Lib\Domain\Collection\CollectionInterface;
use App\Lib\Domain\Criteria\CriteriaInterface;
use App\Lib\Domain\Pagination\PaginationInterface;
use App\Lib\Domain\Sorting\SortingInterface;

interface FileRepositoryInterface
{
    public function findAll(
        ?CriteriaInterface $criteria = null,
        ?PaginationInterface $pagination = null,
        ?SortingInterface $sorting = null
    ): CollectionInterface;
}