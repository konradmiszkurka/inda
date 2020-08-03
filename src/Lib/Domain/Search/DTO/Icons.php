<?php
declare(strict_types=1);

namespace App\Lib\Domain\Search\DTO;

use App\Lib\Domain\Collection\AbstractCollection;

final class Icons extends AbstractCollection
{
    public function getType(): string
    {
        return Icon::class;
    }
}
