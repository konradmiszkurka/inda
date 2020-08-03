<?php

declare(strict_types=1);

namespace App\Modules\User\UI\User\Request;

use App\Modules\Attachment\Domain\Photo\Entity\PhotoEntity;
use App\Modules\User\Domain\User\Interfaces\UserAccountDataInterface;
use JMS\Serializer\Annotation as Serializer;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @OA\Schema(
 *     schema="UpdateUserData",
 *     type="object",
 *     title="User create data",
 *     @OA\Property(property="position", type="string", description="Position of the User"),
 *     @OA\Property(property="firstName", type="string", description="First name of the User"),
 *     @OA\Property(property="lastName", type="string", description="Last name of the User"),
 *     @OA\Property(property="phone", type="string", description="Phone of the User"),
 *     example={
 *          "position": "title",
 *          "phone": "506506506",
 *          "firstName": "test",
 *          "lastName": "testowy"
 *     }
 * )
 */
final class UpdateUserData implements UserAccountDataInterface
{
    /**
     * @Serializer\Type("string")
     * @Serializer\SerializedName("position")
     * @var string
     */
    private $position = '';
    /**
     * @Serializer\Type("string")
     * @Serializer\SerializedName("phone")
     * @var string
     */
    private $phone = '';
    /**
     * @Serializer\Type("string")
     * @Serializer\SerializedName("firstName")
     * * @var string
     */
    private $firstName;
    /**
     * @Serializer\Type("string")
     * @Serializer\SerializedName("lastName")
     * @var string
     */
    private $lastName;
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

    public function getPosition(): string
    {
        return $this->position;
    }

    public function getPhone(): string
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getAvatarFile(): ?PhotoEntity
    {
        return $this->avatarFile;
    }

    public function getAvatarFileNew(): ?UploadedFile
    {
        return $this->avatarFileNew;
    }
}