<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\Product\Interfaces;

use App\Modules\Product\Domain\Price\Entity\PriceEntity;
use Symfony\Component\Validator\Constraints as Assert;

interface DataInterface
{
    public function getName(): ?string;

    public function getPrice(): ?PriceEntity;

    /**
     * @Assert\Length(min="100")
     */
    public function getDescription(): ?string;
}