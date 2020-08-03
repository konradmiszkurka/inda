<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\Price\Interfaces;

interface DataInterface
{
    public function getCurrency(): ?string;

    public function getValue(): ?string;
}