<?php
declare(strict_types=1);

namespace App\Lib\Domain\Error;

use App\Lib\Domain\Collection\AbstractCollection;

class Errors extends AbstractCollection
{
    public function getType(): string
    {
        return Error::class;
    }
}
