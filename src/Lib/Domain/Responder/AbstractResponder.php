<?php
declare(strict_types=1);

namespace App\Lib\Domain\Responder;

use App\Lib\Domain\Error\Error;
use App\Lib\Domain\Error\Errors;
use App\Lib\Domain\Serializer\Serializer;
use App\Lib\Domain\Responder\DTO\PaginationDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

abstract class AbstractResponder
{
    abstract protected function serializeEntity(object $entity): array;

    public function serializeOpenEntity(object $entity): array
    {
        return $this->serializeEntity($entity);
    }

    public function respondDeleted(): Response
    {
        return new JsonResponse(null, 204);
    }

    public function respondUpdated(): Response
    {
        return new JsonResponse(null, 204);
    }

    public function respondResource(object $entity): Response
    {
        return new JsonResponse($this->serializeEntity($entity));
    }

    public function respondCollection(array $collection, ?PaginationDTO $pagination = null): Response
    {
        $response = [
            'data' => array_map(function (object $entity) {
                return $this->serializeEntity($entity);
            }, $collection),
        ];

        return new JsonResponse($response);
    }

    /**
     * @var string|int|null $identifier
     * @var string|null $location
     */
    public function respondCreated($resourceId = null, ?string $location = null): Response
    {
        return new JsonResponse(
            null !== $resourceId ? ['id' => $resourceId] : [], 201,
            null !== $location ? ['Location' => $location] : []
        );
    }

    public function respondBadRequestFromErrors(Errors $errors): Response
    {
        $messages = [];
        /** @var Error $error */
        foreach ($errors as $error) {
            $messages[] = $error->jsonSerialize();
        }

        return new JsonResponse(['messages' => $messages], 400);
    }

    public function respondBadRequestFromViolations(ConstraintViolationListInterface $violationList): Response
    {
        $messages = [];
        /** @var ConstraintViolationInterface $violation */
        foreach ($violationList as $violation) {
            $messages[$violation->getPropertyPath()][] = [
                'code' => $violation->getCode(),
                'message' => $violation->getMessage(),
            ];
        }

        return new JsonResponse(['messages' => $messages], 400);
    }

    public function respondNotFound(): Response
    {
        return new JsonResponse(null, 404);
    }

    public function respondForbidden(): Response
    {
        return new JsonResponse(null, 403);
    }
}
