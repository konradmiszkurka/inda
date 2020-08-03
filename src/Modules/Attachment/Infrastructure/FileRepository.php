<?php
declare(strict_types=1);

namespace App\Modules\Attachment\Infrastructure;

use App\Lib\Domain\Repository\AbstractRepository;
use App\Modules\Attachment\Domain\File\Entity\FileEntity;
use App\Modules\Attachment\Domain\File\Interfaces\FilePersistenceInterface;
use App\Modules\Attachment\Domain\File\Interfaces\FileRepositoryInterface;

final class FileRepository extends AbstractRepository implements FileRepositoryInterface, FilePersistenceInterface
{
    public function persist(FileEntity $entity): void
    {
        $this->entityManager->persist($entity);
    }

    protected function getEntityFQN(): string
    {
        return FileEntity::class;
    }
}