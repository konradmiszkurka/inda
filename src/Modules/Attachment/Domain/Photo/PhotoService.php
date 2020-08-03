<?php
declare(strict_types=1);

namespace App\Modules\Attachment\Domain\Photo;

use App\Modules\Attachment\Domain\Photo\Entity\PhotoEntity;
use App\Modules\Attachment\Domain\Photo\Interfaces\FormDataInterface;
use App\Modules\Attachment\Domain\Photo\Interfaces\PhotoPersistenceInterface;

final class PhotoService
{
    /**
     * @var PhotoPersistenceInterface
     */
    private $persistence;

    public function __construct(PhotoPersistenceInterface $persistence)
    {
        $this->persistence = $persistence;
    }

    /**
     * @param FormDataInterface $data
     * @return PhotoEntity
     */
    public function create(FormDataInterface $data): PhotoEntity
    {
        $entity = new PhotoEntity($data);

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return $entity;
    }

    /**
     * @param PhotoEntity $entity
     * @param FormDataInterface $data
     * @return PhotoEntity
     */
    public function update(PhotoEntity $entity, FormDataInterface $data): PhotoEntity
    {
        $entity->update($data);

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return $entity;
    }

    /**
     * @param PhotoEntity $entity
     * @return bool
     */
    public function remove(PhotoEntity $entity): bool
    {
        $entity->remove();

        $this->persistence->persist($entity);
        $this->persistence->flush();

        return true;
    }
}