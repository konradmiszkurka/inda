<?php

declare(strict_types=1);

namespace App\Modules\Product\Infrastructure;

use App\Lib\Domain\Repository\AbstractRepository;
use App\Modules\Product\Domain\CurrencyLocale\Interfaces\CurrencyLocaleRepositoryInterface;
use App\Modules\Product\Domain\CurrencyLocale\Entity\CurrencyLocaleEntity;
use App\Modules\Product\Domain\CurrencyLocale\Interfaces\CurrencyLocalePersistenceInterface;


final class CurrencyLocaleRepository extends AbstractRepository implements CurrencyLocaleRepositoryInterface, CurrencyLocalePersistenceInterface
{
    public function persist(CurrencyLocaleEntity $entity): void
    {
        $this->entityManager->persist($entity);
    }

    protected function getEntityFQN(): string
    {
        return CurrencyLocaleEntity::class;
    }

    public function findByLocale(string $locale): CurrencyLocaleEntity
    {
        return $this->repository->findOneBy([
            'locale' => $locale
        ]);
    }
}