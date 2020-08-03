<?php

declare(strict_types=1);

namespace App\Modules\Product\Infrastructure;

use App\Lib\Domain\Repository\AbstractRepository;
use App\Modules\Product\Domain\Price\Interfaces\PricePersistenceInterface;
use App\Modules\Product\Domain\Price\Entity\PriceEntity;
use App\Modules\Product\Domain\Price\Interfaces\PriceRepositoryInterface;

final class PriceRepository extends AbstractRepository implements PricePersistenceInterface, PriceRepositoryInterface
{
    public function persist(PriceEntity $entity): void
    {
        $this->entityManager->persist($entity);
    }

    protected function getEntityFQN(): string
    {
        return PriceEntity::class;
    }

    public function find(int $id): PriceEntity
    {
        return $this->repository->find($id);
    }
}