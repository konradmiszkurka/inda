<?php

declare(strict_types=1);

namespace App\Modules\Attachment\Domain\Photo\Entity;

use App\Lib\Domain\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Modules\Attachment\Domain\Photo\Interfaces\FormDataInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="attachment_photo")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class PhotoEntity extends BaseEntity
{
    /**
     * @var string
     * @ORM\Column(type="string", length=256, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="string", length=256, nullable=false)
     */
    private $path;

    /**
     * @var string
     * @ORM\Column(name="mime_type", type="string", length=256, nullable=false)
     */
    private $mimeType;

    public function __construct(FormDataInterface $data)
    {
        $this->populate($data);
    }

    public function update(FormDataInterface $data): void
    {
        $this->populate($data);
    }

    public function remove(): void
    {
        $this->removed();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function populate(FormDataInterface $data): void
    {
        $this->name = $data->getName();
        $this->description = $data->getDescription();
        $this->path = $data->getPath();
        $this->mimeType = $data->getMimeType();
    }
}