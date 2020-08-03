<?php

declare(strict_types=1);

namespace App\Modules\Attachment\UI\File\Responder;

use App\Lib\Domain\Responder\AbstractResponder;
use App\Modules\Attachment\Domain\File\Entity\FileEntity;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="AttachmentFile",
 *     type="object",
 *     title="Attachment File object",
 *     @OA\Property(property="id", type="integer", description="ID of the File"),
 *     @OA\Property(property="path", type="string", description="Path of the File"),
 *     example={
 *          "id": "1",
 *          "path": "path"
 *     }
 * )
 */
final class FileResponder extends AbstractResponder
{
    protected function serializeEntity(object $entity): array
    {
        $serialized = [];
        if ($entity instanceof FileEntity) {
            $serialized = [
                'id' => $entity->getId(),
                'path' => $entity->getPath(),
            ];
        }

        return $serialized;
    }
}