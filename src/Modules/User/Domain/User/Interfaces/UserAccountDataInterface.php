<?php
declare(strict_types=1);

namespace App\Modules\User\Domain\User\Interfaces;

use App\Modules\Attachment\Domain\Photo\Entity\PhotoEntity;
use App\Modules\User\Domain\User\Entity\UserEntity;
use App\Modules\User\UI\Admin\Request\FormAccountData;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

interface UserAccountDataInterface
{
    public function getPosition(): ?string;

    /**
     * @Assert\Length(min="9", max="11")
     */
    public function getPhone(): ?string;

    /**
     * @Assert\NotBlank()
     */
    public function getFirstName(): ?string;

    /**
     * @Assert\NotBlank()
     */
    public function getLastName(): ?string;

    /**
     * @Assert\Length(min = 6)
     */
    public function getPassword(): ?string;

    /**
     * @Assert\Email()
     */
    public function getEmail(): ?string;

    public function getAvatarFile(): ?PhotoEntity;

    public function getAvatarFileNew(): ?UploadedFile;
}