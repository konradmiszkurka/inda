<?php
declare(strict_types=1);

namespace App\Modules\Attachment\Domain\File;

use App\Modules\Attachment\Domain\File\Entity\FileEntity;
use App\Modules\Attachment\Domain\File\Interfaces\FilePersistenceInterface;
use App\Modules\Attachment\Domain\File\Interfaces\FormDataInterface;

final class FileService
{
    /**
     * @var FilePersistenceInterface
     */
    private $persistence;

    public function __construct(FilePersistenceInterface $persistence)
    {
        $this->persistence = $persistence;
    }

    /**
     * @param FormDataInterface $data
     * @return FileEntity
     */
    public function create(FormDataInterface $data): FileEntity
    {
        $entity = new FileEntity($data);

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return $entity;
    }

    /**
     * @param FileEntity $entity
     * @param FormDataInterface $data
     * @return FileEntity
     */
    public function update(FileEntity $entity, FormDataInterface $data): FileEntity
    {
        $entity->update($data);

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return $entity;
    }

    /**
     * @param FileEntity $entity
     * @return bool
     */
    public function remove(FileEntity $entity): bool
    {
        $entity->remove();

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return true;
    }
}