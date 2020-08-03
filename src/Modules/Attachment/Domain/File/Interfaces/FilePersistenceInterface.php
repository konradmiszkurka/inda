<?php
declare(strict_types=1);

namespace App\Modules\Attachment\Domain\File\Interfaces;

use App\Modules\Attachment\Domain\File\Entity\FileEntity;

interface FilePersistenceInterface
{
    public function persist(FileEntity $entity): void;

    public function flush(): void;
}