<?php
declare(strict_types=1);

namespace App\Modules\Attachment\UI\Request;

use App\Modules\Attachment\Domain\File\Interfaces\FormDataInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FormFileData implements FormDataInterface
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
    private $category = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): FormFileData
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): FormFileData
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

    public function setFile(?UploadedFile $file): FormFileData
    {
        $this->file = $file;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): FormFileData
    {
        $this->category = $category;

        return $this;
    }
}