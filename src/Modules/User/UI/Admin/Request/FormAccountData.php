<?php
declare(strict_types=1);

namespace App\Modules\User\UI\Admin\Request;

use App\Modules\Attachment\Domain\Photo\Entity\PhotoEntity;
use App\Modules\User\Domain\User\Entity\UserEntity;
use App\Modules\User\Domain\User\Interfaces\UserAccountDataInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FormAccountData implements UserAccountDataInterface
{
    /**
     * @var UserEntity
     */
    private $user;
    /**
     * @var string|null
     */
    private $position = null;
    /**
     * @var string|null
     */
    private $phone = null;
    /**
     * @var string|null
     */
    private $firstName = null;
    /**
     * @var string|null 
     */
    private $lastName = null;
    /**
     * @var string|null
     */
    private $password = null;
    /**
     * @var string|null
     */
    private $email = null;
    /**
     * @var PhotoEntity|null
     */
    private $avatarFile = null;
    /**
     * @var UploadedFile|null
     */
    private $avatarFileNew = null;

    public function getUser(): UserEntity
    {
        return $this->user;
    }

    public function setUser(UserEntity $user): FormAccountData
    {
        $this->user = $user;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): FormAccountData
    {
        $this->position = $position;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): FormAccountData
    {
        $this->phone = $phone;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): FormAccountData
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): FormAccountData
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): FormAccountData
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): FormAccountData
    {
        $this->email = $email;

        return $this;
    }

    public function getAvatarFile(): ?PhotoEntity
    {
        return $this->avatarFile;
    }

    public function setAvatarFile(?PhotoEntity $avatarFile): FormAccountData
    {
        $this->avatarFile = $avatarFile;
        return $this;
    }

    public function getAvatarFileNew(): ?UploadedFile
    {
        return $this->avatarFileNew;
    }

    public function setAvatarFileNew(?UploadedFile $avatarFileNew): FormAccountData
    {
        $this->avatarFileNew = $avatarFileNew;
        return $this;
    }
}