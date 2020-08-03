<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\Price;

use App\Modules\Product\Domain\Price\Entity\PriceEntity;
use App\Modules\Product\Domain\Price\Interfaces\DataInterface;
use App\Modules\Product\Domain\Price\Interfaces\PricePersistenceInterface;

class PriceService
{
    /**
     * @var PricePersistenceInterface
     */
    private $persistence;

    public function __construct(PricePersistenceInterface $persistence)
    {
        $this->persistence = $persistence;
    }

    public function create(DataInterface $data): PriceEntity
    {
        $entity = new PriceEntity($data);

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return $entity;
    }

    public function update(PriceEntity $entity, DataInterface $data): PriceEntity
    {
        $entity->update($data);

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return $entity;
    }

    public function remove(PriceEntity $entity): bool
    {
        $entity->remove();

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return true;
    }
}