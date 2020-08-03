<?php

declare(strict_types=1);

namespace App\Modules\Product\Infrastructure;

use App\Lib\Domain\Repository\AbstractRepository;
use App\Modules\Product\Domain\Product\Entity\ProductEntity;
use App\Modules\Product\Domain\Product\Interfaces\ProductPersistenceInterface;
use App\Modules\Product\Domain\Product\Interfaces\ProductRepositoryInterface;

final class ProductRepository extends AbstractRepository implements ProductPersistenceInterface, ProductRepositoryInterface
{
    public function persist(ProductEntity $entity): void
    {
        $this->entityManager->persist($entity);
    }

    protected function getEntityFQN(): string
    {
        return ProductEntity::class;
    }

    public function find(int $id): ProductEntity
    {
        return $this->repository->find($id);
    }
}