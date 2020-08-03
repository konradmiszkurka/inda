<?php

declare(strict_types=1);

namespace App\Modules\User\UI\User\Action;

use App\Modules\User\Application\UserApplicationService;
use App\Modules\User\UI\Admin\Request\FormUserData;
use App\Modules\User\UI\User\Request\CreateUserData;
use App\Modules\User\UI\User\Responder\UserResponder;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @OA\Post(
 *     path="/oapi/users/",
 *     summary="Create an User",
 *     tags={"User"},
 *     @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/CreateUserData")
 *     ),
 *     @OA\Response(
 *         response="201",
 *         description="User has been created.",
 *         @OA\JsonContent(
 *              description="ID of the new user.",
 *              @OA\Property(property="id", type="string", example="1"),
 *         ),
 *     ),
 *     @OA\Response(response="400", description="Provided data is invalid."),
 * )
 */
final class CreateUser
{
    /**
     * @var UserResponder
     */
    private $userResponder;
    /**
     * @var UserApplicationService
     */
    private $userService;

    public function __construct(
        UserResponder $userResponder,
        UserApplicationService $userService
    ) {
        $this->userResponder = $userResponder;
        $this->userService = $userService;
    }

    /**
     * @Route(
     *     path="/oapi/users/",
     *     methods={"POST"}
     * )
     * @ParamConverter("data", converter="fos_rest.request_body")
     * @param CreateUserData $data
     * @param ConstraintViolationListInterface $violationList
     * @return Response
     */
    public function create(
        CreateUserData $data,
        ConstraintViolationListInterface $violationList
    ): Response {
        if (count($violationList)) {
            return $this->userResponder->respondBadRequestFromViolations($violationList);
        }

        $result = $this->userService->createUser(
            (new FormUserData())
                ->setPhone($data->getPhone())
                ->setEmail($data->getEmail())
                ->setLastName($data->getLastName())
                ->setFirstName($data->getFirstName())
                ->setPassword($data->getPassword())
                ->setRole($data->getRole())
                ->setUserName($data->getUserName())
        );

        if (false === $result->isSuccessful()) {
            return $this->userResponder->respondBadRequestFromErrors($result->getErrors());
        }

        return $this->userResponder->respondCreated($result->getIdentifier());
    }
}