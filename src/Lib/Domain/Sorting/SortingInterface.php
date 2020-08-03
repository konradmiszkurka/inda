<?php
declare(strict_types=1);

namespace App\Lib\Domain\Sorting;

interface SortingInterface
{
    public function getField(): string;

    public function getOrder(): string;
}