<?php

declare(strict_types=1);

namespace App\Modules\User\UI\User\Action;

use App\Modules\User\Domain\User\Interfaces\UserRepositoryInterface;
use App\Modules\User\UI\User\Responder\UserResponder;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @OA\Get(
 *     path="/api/users/my/",
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
final class GetMyData
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var UserResponder
     */
    private $userResponder;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserResponder $userResponder,
        TokenStorageInterface $tokenStorage
    ) {
        $this->userRepository = $userRepository;
        $this->userResponder = $userResponder;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route(
     *     path="/api/users/my",
     *     methods={"GET"}
     * )
     * @return Response
     */
    public function getData(): Response
    {
        $userToken = $this->tokenStorage->getToken();
        if ($userToken === null) {
            return $this->userResponder->respondNotFound();
        }

        $user = $this->userRepository->find($userToken->getUser()->getId());
        if ($user === null) {
            return $this->userResponder->respondNotFound();
        }

        return $this->userResponder->respondResource($user);
    }
}