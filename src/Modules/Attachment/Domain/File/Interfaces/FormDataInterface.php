<?php
declare(strict_types=1);

namespace App\Modules\Attachment\Domain\File\Interfaces;

use App\Modules\Attachment\Domain\File\Enum\CategoryEnum;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

interface FormDataInterface
{
    /**
     * @Assert\Length(min="3", max="128")
     * @Assert\NotNull()
     */
    public function getName(): ?string;

    /**
     * @Assert\Length(min="3")
     */
    public function getDescription(): ?string;

    public function setPath(?string $path): void;

    public function getPath(): ?string;

    public function setMimeType(?string $mimeType): void;

    public function getMimeType(): ?string;

    public function getCategory(): ?string;

    public function getFile(): ?UploadedFile;
}