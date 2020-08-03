<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\Product\Interfaces;

use Symfony\Component\Validator\Constraints as Assert;

interface DataInterface
{
    /**
     * @Assert\NotNull()
     */
    public function getName(): string;

    /**
     * @Assert\NotNull()
     */
    public function getPrice(): int;

    /**
     * @Assert\Length(min="100")
     * @Assert\NotNull()
     */
    public function getDescription(): string;
}