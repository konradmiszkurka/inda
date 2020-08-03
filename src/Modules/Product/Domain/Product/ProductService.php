<?php

declare(strict_types=1);

namespace App\Modules\Product\Domain\Product;


use App\Modules\Product\Domain\Product\Entity\ProductEntity;
use App\Modules\Product\Domain\Product\Interfaces\DataInterface;
use App\Modules\Product\Domain\Product\Interfaces\ProductPersistenceInterface;

class ProductService
{
    /**
     * @var ProductPersistenceInterface
     */
    private $persistence;

    public function __construct(ProductPersistenceInterface $persistence)
    {
        $this->persistence = $persistence;
    }

    public function create(DataInterface $data): ProductEntity
    {
        $entity = new ProductEntity($data);

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return $entity;
    }

    public function update(ProductEntity $entity, DataInterface $data): ProductEntity
    {
        $entity->update($data);

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return $entity;
    }

    public function remove(ProductEntity $entity): bool
    {
        $entity->remove();

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return true;
    }
}