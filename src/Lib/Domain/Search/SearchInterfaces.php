<?php
declare(strict_types=1);

namespace App\Lib\Domain\Search;

use App\Lib\Domain\Search\DTO\Elements;

interface SearchInterfaces
{
    public function search(string $query): Elements;
}