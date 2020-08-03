<?php
declare(strict_types=1);

namespace App\Modules\Attachment\UI\Request;

use App\Modules\Attachment\Domain\Photo\Interfaces\PhotoBase64DataInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class Base64PhotoData implements PhotoBase64DataInterface
{
    /**
     * @var string
     */
    private $name;
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
     * @var string
     */
    private $base64;
    /**
     * @var string|null
     */
    private $pathFile = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Base64PhotoData
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Base64PhotoData
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

    public function setFile(?UploadedFile $file): Base64PhotoData
    {
        $this->file = $file;
        return $this;
    }

    public function getBase64(): string
    {
        return $this->base64;
    }

    public function setBase64(string $base64): Base64PhotoData
    {
        $this->base64 = $base64;
        return $this;
    }

    public function getPathFile(): ?string
    {
        return $this->pathFile;
    }

    public function setPathFile(?string $pathFile): Base64PhotoData
    {
        $this->pathFile = $pathFile;
        return $this;
    }
}