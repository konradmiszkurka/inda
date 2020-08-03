<?php

declare(strict_types=1);

namespace App\Modules\User\UI\User\Request;

use App\Modules\User\Domain\User\Enum\RoleEnum;
use App\Modules\User\Domain\User\Interfaces\RequestUserDataInterface;
use JMS\Serializer\Annotation as Serializer;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CreateUserData",
 *     type="object",
 *     title="User create data",
 *     @OA\Property(property="firstName", type="string", description="First name of the User"),
 *     @OA\Property(property="lastName", type="string", description="Last name of the User"),
 *     @OA\Property(property="password", type="string", description="Password of the User"),
 *     @OA\Property(property="email", type="string", description="Email of the User"),
 *     @OA\Property(property="phone", type="string", description="Phone of the User"),
 *     example={
 *          "phone": "506506506",
 *          "firstName": "test",
 *          "lastName": "testowy",
 *          "password": "password",
 *          "email": "test@zoneity.es"
 *     }
 * )
 */
final class CreateUserData implements RequestUserDataInterface
{
    /**
     * @Serializer\Type("string")
     * @var string
     */
    private $phone = '';
    /**
     * @Serializer\Type("string")
     * @Serializer\SerializedName("firstName")
     * @var string
     */
    private $firstName = '';
    /**
     * @Serializer\Type("string")
     * @Serializer\SerializedName("lastName")
     * @var string
     */
    private $lastName = '';
    /**
     * @Serializer\Type("string")
     * @var string
     */
    private $password;
    /**
     * @Serializer\Type("string")
     * @var string
     */
    private $email;

    public function getUserName(): string
    {
        return str_replace(['@', '.'], '_', $this->email);
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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): string
    {
        return RoleEnum::ROLE_USER;
    }
}