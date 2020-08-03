<?php
declare(strict_types=1);

namespace App\Lib\Domain\Collection;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use JsonSerializable;

interface CollectionInterface extends ArrayAccess, IteratorAggregate, JsonSerializable, Countable
{
    public function getAll(): array;

    public function getTotal(): int;
}