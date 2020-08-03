<?php
declare(strict_types=1);

namespace App\Modules\Attachment\UI\Request;

use App\Modules\Attachment\Domain\Photo\Interfaces\FormDataInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FormPhotoData implements FormDataInterface
{
    /**
     * @var string|null
     */
    private $name = null;
    /**
     * @var string|null
     */
    private $description = null;
    /**
     * @var string|null
     */
    private $path = null;
    /**
     * @var string|null
     */
    private $mimeType = null;
    /**
     * @var UploadedFile|null
     */
    private $file = null;
    /**
     * @var string|null
     */
    private $pathFile = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): FormPhotoData
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): FormPhotoData
    {
        $this->description = $description;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(?UploadedFile $file): FormPhotoData
    {
        $this->file = $file;

        return $this;
    }

    public function getPathFile(): ?string
    {
        return $this->pathFile;
    }

    public function setPathFile(?string $pathFile): FormPhotoData
    {
        $this->pathFile = $pathFile;
        return $this;
    }
}