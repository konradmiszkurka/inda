<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\User\Entity;

use App\Lib\Domain\User\BaseUserEntity;
use App\Modules\Attachment\Domain\Photo\Entity\PhotoEntity;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use KevinPapst\AdminLTEBundle\Model\UserInterface;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Lib\Domain\Helper\StringHelper;
use App\Lib\Domain\User\UserEntityInterface;
use App\Modules\User\Domain\User\Enum\RoleEnum;
use App\Modules\User\Domain\User\Enum\TypeAvatarEnum;
use App\Modules\User\Domain\User\Interfaces\ChangePasswordDataInterface;
use App\Modules\User\Domain\User\Interfaces\UserAccountDataInterface;
use App\Modules\User\Domain\User\Interfaces\UserDataInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user_")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class UserEntity extends BaseUserEntity implements UserEntityInterface, UserInterface
{
    /**
     * @var string|null
     * @ORM\Column(type="string", length=11, nullable=true)
     */
    private $phone;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string", nullable=false)
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(name="last_name", type="string", nullable=false)
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(type="string", length=256, nullable=false)
     */
    private $hash;

    /**
     * @var string|null
     * @ORM\Column(type="UserUserAvatarEnum", nullable=true, options={"default" : "default"})
     * @DoctrineAssert\Enum(entity="App\Modules\User\Domain\User\Enum\TypeAvatarEnum")
     */
    private $avatarType;
    /**
     * @var PhotoEntity|null
     * @ORM\ManyToOne(targetEntity="App\Modules\Attachment\Domain\Photo\Entity\PhotoEntity")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=true)
     */
    private $avatarPhoto;
    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true, options={"default" : 0})
     */
    private $passwordChangeRequired = false;

    public function create(UserDataInterface $data)
    {
        $this->hash = StringHelper::uniqidReal(16);
        $this->avatarType = TypeAvatarEnum::INITIAL;
        $this->populate($data);
        $this->setPlainPassword($data->getPassword());
        $this->activate();
        $this->created();
    }

    public function update(UserDataInterface $data): void
    {
        $this->populate($data);
        if ($data->getPassword()) {
            $this->setPlainPassword($data->getPassword());
        }
        TypeAvatarEnum::removedCachedFile();
        $this->updated();
    }

    public function updateAccount(UserAccountDataInterface $data): void
    {
        $this->email = $data->getEmail();
        $this->emailCanonical = $data->getEmail();
        $this->position = $data->getPosition();
        $this->phone = $data->getPhone();
        $this->firstName = $data->getFirstName();
        $this->lastName = $data->getLastName();
        if ($data->getAvatarFile()) {
            $this->avatarPhoto = $data->getAvatarFile();
            TypeAvatarEnum::removedCachedFile();
        }
        if ($data->getPassword()) {
            $this->setPlainPassword($data->getPassword());
        }
    }

    public function changePasswordAccount(ChangePasswordDataInterface $data): void
    {
        $this->setPlainPassword($data->getPassword1());
        $this->passwordChangeRequired = false;
    }

    public function remove(): void
    {
        $this->removed();
    }

    public function activate(): void
    {
        $this->enabled = true;
    }

    public function deactivate(): void
    {
        $this->enabled = false;
    }

    public function getAvatarType(): ?string
    {
        return $this->avatarType;
    }

    public function getUserAvatar(string $type): string
    {
        TypeAvatarEnum::assertValidChoice($type);

        return TypeAvatarEnum::avatarGenerate($this, $type);
    }

    //Interfaces
    public function getAvatar()
    {
        return TypeAvatarEnum::avatarGenerate($this);
    }

    public function updateAvatarTypeAccount(string $avatarType): void
    {
        TypeAvatarEnum::assertValidChoice($avatarType);

        $this->avatarType = $avatarType;
        TypeAvatarEnum::removedCachedFile();
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getRoleName(): string
    {
        return RoleEnum::getConstName($this->getRoles());
    }

    public function isPasswordChangeRequired(): bool
    {
        return $this->passwordChangeRequired;
    }

    public function getName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function getMemberSince()
    {
        return $this->getCreatedAt();
    }

    public function isOnline()
    {
        return $this->isEnabled();
    }

    public function getIdentifier()
    {
        return $this->getId();
    }

    public function getTitle()
    {
        return $this->getName();
    }
    public function getAvatarPhoto(): ?PhotoEntity
    {
        return $this->avatarPhoto;
    }

    public function populate(UserDataInterface $data): void
    {
        RoleEnum::assertValidChoice($data->getRole());

        $this->username = $data->getUsername();
        $this->usernameCanonical = $data->getUserName();
        $this->phone = $data->getPhone();
        $this->email = $data->getEmail();
        $this->emailCanonical = $data->getEmail();
        $this->firstName = $data->getFirstName();
        $this->lastName = $data->getLastName();
        $this->roles = RoleEnum::getRealRole($data->getRole());
    }
}