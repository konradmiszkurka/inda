<?php

declare(strict_types=1);

namespace App\Modules\Attachment\UI\Photo\Responder;

use App\Lib\Domain\Responder\AbstractResponder;
use App\Modules\Attachment\Domain\Photo\Entity\PhotoEntity;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="AttachmentPhoto",
 *     type="object",
 *     title="Attachment Photo object",
 *     @OA\Property(property="id", type="integer", description="ID of the Photo"),
 *     @OA\Property(property="path", type="string", description="Path of the Photo"),
 *     @OA\Property(property="base64", type="string", description="Base64 of the Photo"),
 *     example={
 *          "id": "1",
 *          "path": "path",
 *          "base64": "base64"
 *     }
 * )
 */
final class PhotoResponder extends AbstractResponder
{
    protected function serializeEntity(object $entity): array
    {
        $serialized = [];
        if ($entity instanceof PhotoEntity) {
            $serialized = [
                'id' => $entity->getId(),
                'path' => $entity->getPath(),
                'base64' => file_exists($entity->getPath()) ? base64_encode(file_get_contents($entity->getPath())) : '',
            ];
        }

        return $serialized;
    }
}