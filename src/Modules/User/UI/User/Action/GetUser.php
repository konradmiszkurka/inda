<?php
declare(strict_types=1);

namespace App\Modules\User\UI\User\Action;

use App\Modules\User\Domain\User\Interfaces\UserRepositoryInterface;
use App\Modules\User\UI\User\Responder\UserResponder;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @OA\Get(
 *     path="/oapi/users/{userId}/",
 *     summary="Get the User",
 *     tags={"User"},
 *     @OA\Parameter(ref="#/components/parameters/userId"),
 *     @OA\Response(
 *         response="200",
 *         description="User details",
 *         @OA\JsonContent(ref="#/components/schemas/User"),
 *     ),
 *     @OA\Response(response="404", description="User was not found."),
 * )
 * @OA\Get(
 *     path="/api/users/{userId}/",
 *     summary="Get the User",
 *     tags={"User"},
 *     @OA\Parameter(ref="#/components/parameters/userId"),
 *     @OA\Response(
 *         response="200",
 *         description="User details",
 *         @OA\JsonContent(ref="#/components/schemas/User"),
 *     ),
 *     @OA\Response(response="404", description="User was not found."),
 * )
 */
final class GetUser
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var UserResponder
     */
    private $userResponder;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserResponder $userResponder
    ) {
        $this->userRepository = $userRepository;
        $this->userResponder = $userResponder;
    }

    /**
     * @Route(
     *     path="/api/users/{userId}",
     *     methods={"GET"}
     * )
     * @Route(
     *     path="/oapi/users/{userId}",
     *     methods={"GET"}
     * )
     * @param int   $userId
     * @return Response
     */
    public function getOne(
        int $userId
    ): Response {
        $user = $this->userRepository->find($userId);
        if ($user === null) {
            return $this->userResponder->respondNotFound();
        }

        return $this->userResponder->respondResource($user);
    }
}