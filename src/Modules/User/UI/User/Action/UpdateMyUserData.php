<?php

declare(strict_types=1);

namespace App\Modules\User\UI\User\Action;

use App\Modules\User\Application\UserApplicationService;
use App\Modules\User\Domain\User\Interfaces\UserRepositoryInterface;
use App\Modules\User\UI\User\Request\UpdateUserData;
use App\Modules\User\UI\User\Responder\UserResponder;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @OA\Put(
 *     path="/oapi/users/my",
 *     summary="Update data of the User",
 *     tags={"User"},
 *     @OA\Parameter(ref="#/components/parameters/userId"),
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/UpdateUserData")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="User details",
 *         @OA\JsonContent(ref="#/components/schemas/User"),
 *     ),
 *     @OA\Response(response="400", description="Provided data is invalid."),
 *     @OA\Response(response="404", description="The User was not found."),
 * )
 */
final class UpdateMyUserData
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
     * @var UserApplicationService
     */
    private $userService;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserResponder $userResponder,
        UserApplicationService $userService,
        TokenStorageInterface $tokenStorage
    ) {
        $this->userRepository = $userRepository;
        $this->userResponder = $userResponder;
        $this->userService = $userService;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route(
     *     path="/oapi/users/my",
     *     methods={"PUT"}
     * )
     * @ParamConverter("data", converter="fos_rest.request_body")
     * @param UpdateUserData $data
     * @param ConstraintViolationListInterface $violationList
     * @return Response
     */
    public function update(
        UpdateUserData $data,
        ConstraintViolationListInterface $violationList
    ): Response {
        $userToken = $this->tokenStorage->getToken();
        if ($userToken === null) {
            return $this->userResponder->respondNotFound();
        }

        $user = $this->userRepository->find($userToken->getUser()->getId());
        if ($user === null) {
            return $this->userResponder->respondNotFound();
        }

        if (count($violationList)) {
            return $this->userResponder->respondBadRequestFromViolations($violationList);
        }

        $data->setEmail($user->getEmail());
        $result = $this->userService->updateAccount($user, $data);

        if (false === $result->isSuccessful()) {
            return $this->userResponder->respondBadRequestFromErrors($result->getErrors());
        }

        return $this->userResponder->respondResource($user);
    }
}