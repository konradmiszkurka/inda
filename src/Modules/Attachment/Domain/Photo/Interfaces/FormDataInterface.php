<?php
declare(strict_types=1);

namespace App\Modules\Attachment\Domain\Photo\Interfaces;

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

    /**
     * Assert\File(
     *     mimeTypes = {
     *         "image/jpeg",
     *         "image/png"
     *     },
     *     mimeTypesMessage = "Please upload a valid photo"
     * )
     */
    public function getFile(): ?UploadedFile;

    public function getPathFile(): ?string;
}