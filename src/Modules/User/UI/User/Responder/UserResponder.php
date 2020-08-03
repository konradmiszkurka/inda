<?php

declare(strict_types=1);

namespace App\Modules\User\UI\User\Responder;

use App\Lib\Domain\Responder\AbstractResponder;
use App\Modules\User\Domain\User\Entity\UserEntity;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User object",
 *     @OA\Property(property="id", type="string", description="ID of the User."),
 *     @OA\Property(property="hash", type="string", description="Hash of the User."),
 *     @OA\Property(property="firstName", type="string", description="First name of the User"),
 *     @OA\Property(property="lastName", type="string", description="Last name of the User"),
 *     @OA\Property(property="email", type="string", description="Email of the User"),
 *     @OA\Property(property="avatar", type="string", description="Avatar of the User"),
 *     @OA\Property(property="createdAt", type="integer", description="Created date of the User, expressed in timestamp."),
 *     example={
 *          "id": "1",
 *          "firstName": "test",
 *          "lastName": "testowy",
 *          "email": "test@zoneity.es",
 *          "avatar": "avatar.jpg",
 *          "createdAt": 1569422010
 *     }
 * )
 */
final class UserResponder extends AbstractResponder
{
    protected function serializeEntity(object $entity): array
    {
        $serialized = [];
        if ($entity instanceof UserEntity) {
            $serialized = [
                'id' => $entity->getId(),
                'firstName' => $entity->getFirstName(),
                'lastName' => $entity->getLastName(),
                'email' => $entity->getEmail(),
                'avatar' => $entity->getAvatar(),
                'createdAt' => $entity->getCreatedAt(),
            ];
        }

        return $serialized;
    }
}