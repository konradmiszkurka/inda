<?php
declare(strict_types=1);

namespace App\Modules\User\Domain\User\Interfaces;

use App\Lib\Domain\Collection\CollectionInterface;
use App\Lib\Domain\Criteria\CriteriaInterface;
use App\Lib\Domain\Pagination\PaginationInterface;
use App\Lib\Domain\Sorting\SortingInterface;
use App\Modules\User\Domain\User\Entity\UserEntity;

interface UserRepositoryInterface
{
    public function find(int $id): ?UserEntity;

    public function findByEmail(string $email): ?UserEntity;
}