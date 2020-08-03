<?php

declare(strict_types=1);

namespace App\Modules\Attachment\Infrastructure;

use App\Lib\Domain\Repository\AbstractRepository;
use App\Modules\Attachment\Domain\Photo\Entity\PhotoEntity;
use App\Modules\Attachment\Domain\Photo\Interfaces\PhotoPersistenceInterface;
use App\Modules\Attachment\Domain\Photo\Interfaces\PhotoRepositoryInterface;

final class PhotoRepository extends AbstractRepository implements PhotoRepositoryInterface, PhotoPersistenceInterface
{
    public function persist(PhotoEntity $entity): void
    {
        $this->entityManager->persist($entity);
    }

    protected function getEntityFQN(): string
    {
        return PhotoEntity::class;
    }

    public function find(int $id): ?PhotoEntity
    {
        return $this->repository->find($id);
    }
}