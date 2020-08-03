<?php

declare(strict_types=1);

namespace App\Modules\User\UI\User\Request;

use App\Modules\User\Domain\User\Interfaces\ChangePasswordDataInterface;
use JMS\Serializer\Annotation as Serializer;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="PasswordUserData",
 *     type="object",
 *     title="User create data",
 *     @OA\Property(property="oldPassword", type="string", description="Old password of the User"),
 *     @OA\Property(property="password1", type="string", description="New password of the User"),
 *     @OA\Property(property="password2", type="string", description="New password repeat of the User"),
 *     example={
 *          "oldPassword": "test",
 *          "password1": "testNew",
 *          "password2": "testNew"
 *     }
 * )
 */
final class PasswordUserData implements ChangePasswordDataInterface
{
    /**
     * @Serializer\Type("string")
     * @Serializer\SerializedName("oldPassword")
     * @var string|null
     */
    private $oldPassword = null;
    /**
     * @Serializer\Type("string")
     * @var string|null
     */
    private $password1 = null;
    /**
     * @Serializer\Type("string")
     * @var string|null
     */
    private $password2 = null;

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function getPassword1(): ?string
    {
        return $this->password1;
    }

    public function getPassword2(): ?string
    {
        return $this->password2;
    }
}