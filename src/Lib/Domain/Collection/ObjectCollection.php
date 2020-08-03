<?php
declare(strict_types=1);

namespace App\Lib\Domain\Collection;

final class ObjectCollection extends AbstractCollection
{
    public function getType(): string
    {
        return 'object';
    }

    protected function isValidType($element): bool
    {
        return is_object($element);
    }
}