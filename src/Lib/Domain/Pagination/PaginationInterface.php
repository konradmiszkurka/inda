<?php
declare(strict_types=1);

namespace App\Lib\Domain\Pagination;

interface PaginationInterface
{
    public function getPage(): int;

    public function getLimit(): int;
}