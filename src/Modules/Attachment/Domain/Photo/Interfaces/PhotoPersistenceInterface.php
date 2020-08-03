<?php
declare(strict_types=1);

namespace App\Modules\Attachment\Domain\Photo\Interfaces;

use App\Modules\Attachment\Domain\Photo\Entity\PhotoEntity;

interface PhotoPersistenceInterface
{
    public function persist(PhotoEntity $entity): void;

    public function flush(): void;
}