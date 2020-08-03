<?php

declare(strict_types=1);

namespace App\Modules\User\UI\Admin\Controller;

use App\Lib\Application\Notification\NotificationNames;
use App\Modules\Main\UI\Controller\Security;
use App\Modules\User\Application\UserApplicationService;
use App\Modules\User\Application\Voter\UserVoter;
use App\Modules\User\Domain\User\Entity\UserEntity;
use App\Modules\User\Domain\User\Enum\RoleEnum;
use App\Modules\User\Domain\User\Interfaces\UserRepositoryInterface;
use App\Modules\User\UI\Admin\Form\UserType;
use App\Modules\User\UI\Admin\Request\FormUserData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/user")
 */
final class UserController extends AbstractController
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var UserApplicationService
     */
    private $application;
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TranslatorInterface $translator,
        UserApplicationService $application,
        UserRepositoryInterface $repository
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->translator = $translator;
        $this->application = $application;
        $this->repository = $repository;
    }

    /**
     * @Route("/create")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request): Response
    {
        if (!$this->authorizationChecker->isGranted(UserVoter::CREATE)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $data = (new FormUserData());

        $roles = RoleEnum::getReadableValues();
        unset($roles[RoleEnum::ROLE_USER]);
        if (!$this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            unset($roles[RoleEnum::ROLE_SUPER_ADMIN]);
        }

        $form = $this
            ->createForm(UserType::class, $data, ['roles' => array_flip($roles)])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->application->createUser($data);

            if (true === $result->isSuccessful()) {
                $this->addFlash(NotificationNames::SUCCESS, $this->translator->trans('Added user', [], 'admin_user'));

                return $this->redirectToRoute(
                    'app_modules_user_ui_admin_user_edit',
                    ['id' => $result->getIdentifier()]
                );
            }
        }

        return $this->render(
            '@Oculux/Pages/form.html.twig',
            [
                'titleBox' => $this->translator->trans('User form', [], 'admin_user'),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit")
     * @param Request $request
     * @param UserEntity $entity
     * @return Response
     */
    public function edit(Request $request, UserEntity $entity): Response
    {
        if (!$this->authorizationChecker->isGranted(UserVoter::SHOW, $entity)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $data = (new FormUserData())
            ->setUserName($entity->getUsername())
            ->setFirstName($entity->getFirstName())
            ->setLastName($entity->getLastName())
            ->setEmail($entity->getEmail())
            ->setRole(RoleEnum::getConst($entity->getRoles()));

        $roles = RoleEnum::getReadableValues();
        unset($roles[RoleEnum::ROLE_USER]);
        if (!$this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            unset($roles[RoleEnum::ROLE_SUPER_ADMIN]);
        }

        $form = $this
            ->createForm(UserType::class, $data, ['password' => false, 'roles' => array_flip($roles)])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->authorizationChecker->isGranted(UserVoter::EDIT, $entity)) {
                return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
            }

            $result = $this->application->updateUser($entity, $data);

            if (true === $result->isSuccessful()) {
                $this->addFlash(
                    NotificationNames::SUCCESS,
                    $this->translator->trans(
                        'Edited user `%name%`',
                        ['%name%' => $entity->getUsername(),],
                        'admin_user'
                    )
                );

                return $this->redirectToRoute('app_modules_user_ui_admin_user_lists');
            }
        }

        return $this->render(
            '@Oculux/Pages/form.html.twig',
            [
                'titleBox' => $this->translator->trans('User form', [], 'admin_user'),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/active")
     * @param UserEntity $entity
     * @return Response
     */
    public function active(UserEntity $entity): Response
    {
        if (!$this->authorizationChecker->isGranted(UserVoter::EDIT, $entity)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $result = $this->application->activeUser($entity);

        if (true === $result->isSuccessful()) {
            $this->addFlash(
                NotificationNames::INFO,
                $this->translator->trans(
                    'Activate user `%name%`',
                    ['%name%' => $entity->getUsername(),],
                    'admin_user'
                )
            );

            return $this->redirectToRoute('app_modules_user_ui_admin_user_lists');
        }
    }

    /**
     * @Route("/{id}/deactivate")
     * @param UserEntity $entity
     * @return Response
     */
    public function deactivate(UserEntity $entity): Response
    {
        if (!$this->authorizationChecker->isGranted(UserVoter::EDIT, $entity)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $result = $this->application->deactivateUser($entity);

        if (true === $result->isSuccessful()) {
            $this->addFlash(
                NotificationNames::INFO,
                $this->translator->trans(
                    'Deactivate user `%name%`',
                    ['%name%' => $entity->getUsername(),],
                    'admin_user'
                )
            );

            return $this->redirectToRoute('app_modules_user_ui_admin_user_lists');
        }
    }

    /**
     * @Route("/{id}/remove")
     * @param UserEntity $entity
     * @return Response
     */
    public function remove(UserEntity $entity): Response
    {
        if (!$this->authorizationChecker->isGranted(UserVoter::REMOVE, $entity)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $result = $this->application->removeUser($entity);

        if (true === $result->isSuccessful()) {
            $this->addFlash(
                NotificationNames::WARNING,
                $this->translator->trans(
                    'Removed user `%name%`',
                    ['%name%' => $entity->getUsername(),],
                    'admin_user'
                )
            );

            return $this->redirectToRoute('app_modules_user_ui_admin_user_lists');
        }
    }

    /**
     * @Route("/lists")
     * @param Request $request
     * @return Response
     */
    public function lists(Request $request): Response
    {
        if (!$this->authorizationChecker->isGranted(UserVoter::LISTS)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $table = $this->application->getUsersListDataTable()
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render(
            'user/users.html.twig',
            [
                'titleBox' => $this->translator->trans('Users list', [], 'admin_user'),
                'datatable' => $table,
                'paths' => [
                    'create' => 'app_modules_user_ui_admin_user_create',
                ],
            ]
        );
    }
}
