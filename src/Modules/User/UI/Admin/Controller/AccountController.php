<?php
declare(strict_types=1);

namespace App\Modules\User\UI\Admin\Controller;

use App\Lib\Application\Notification\NotificationNames;
use App\Modules\Main\UI\Controller\Security;
use App\Modules\User\Application\UserApplicationService;
use App\Modules\User\Application\Voter\UserVoter;
use App\Modules\User\Domain\User\Entity\UserEntity;
use App\Modules\User\Domain\User\Enum\TypeAvatarEnum;
use App\Modules\User\UI\Admin\Form\UserAccountType;
use App\Modules\User\UI\Admin\Request\FormAccountData;
use App\Modules\User\UI\Admin\Request\FormChangePasswordData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/user")
 */
final class AccountController extends AbstractController
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

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TranslatorInterface $translator,
        UserApplicationService $application
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->translator = $translator;
        $this->application = $application;
    }

    /**
     * @Route("/account")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function account(Request $request): Response
    {
        /** @var UserEntity $entity */
        $entity = $this->getUser();

        $data = (new FormAccountData())
            ->setFirstName($entity->getFirstName())
            ->setLastName($entity->getLastName())
            ->setEmail($entity->getEmail())
            ->setAvatarFile($entity->getAvatarPhoto());
        $form = $this
            ->createForm(UserAccountType::class, $data)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->authorizationChecker->isGranted(UserVoter::EDIT_ACCOUNT, $entity)) {
                return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
            }

            $result = $this->application->updateAccount($entity, $data);

            if (true === $result->isSuccessful()) {
                $this->addFlash(NotificationNames::SUCCESS,
                    $this->translator->trans('Changed user data', [], 'admin_user'));

                return $this->redirectToRoute('app_modules_user_ui_admin_account_account');
            }
        }

        return $this->render(
            'user/userAccountForm.html.twig',
            [
                'titleBox' => $this->translator->trans('User profile', [], 'admin_user'),
                'avatarType' => $entity->getAvatarType(),
                'avatars' => [
                    TypeAvatarEnum::DEFAULT => $entity->getUserAvatar(TypeAvatarEnum::DEFAULT),
                    TypeAvatarEnum::FILE => $entity->getUserAvatar(TypeAvatarEnum::FILE),
                    TypeAvatarEnum::INITIAL => $entity->getUserAvatar(TypeAvatarEnum::INITIAL),
                    TypeAvatarEnum::GRAVATAR => $entity->getUserAvatar(TypeAvatarEnum::GRAVATAR),
                ],
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/change-avatar-type/{avatarType}")
     * @param string $avatarType
     * @return Response
     */
    public function changeAvatarType(string $avatarType): Response
    {
        /** @var UserEntity $entity */
        $entity = $this->getUser();

        if (!$this->authorizationChecker->isGranted(UserVoter::EDIT_ACCOUNT, $entity)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $result = $this->application->changeAvatarTypeAccount($entity, $avatarType);

        if (true === $result->isSuccessful()) {
            $this->addFlash(
                NotificationNames::INFO,
                $this->translator->trans(
                    'Avatar type changed',
                    [],
                    'admin_user'
                )
            );

            return $this->redirectToRoute('app_modules_user_ui_admin_account_account');
        }
    }

    /**
     * @Route("/change-password")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function changePassword(Request $request): Response
    {
        /** @var UserEntity $entity */
        $entity = $this->getUser();

        if (!$this->authorizationChecker->isGranted(UserVoter::EDIT_ACCOUNT, $entity)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $data = (new FormChangePasswordData());

        if ($request->request->get('old_password') && $request->request->get('_password1') && $request->request->get('_password2')) {
            if ($entity->getDn()) {
                $this->addFlash(NotificationNames::WARNING,
                    $this->translator->trans('You can`t change password here.', [], 'admin_user'));
                return $this->redirectToRoute('app_modules_main_ui_dashboard_admin');
            }

            $data
                ->setOldPassword($request->request->get('old_password'))
                ->setPassword1($request->request->get('_password1'))
                ->setPassword2($request->request->get('_password2'));

            $result = $this->application->changePasswordAccount($entity, $data);

            if (true === $result->isSuccessful()) {
                $this->addFlash(NotificationNames::SUCCESS,
                    $this->translator->trans('Password was changed', [], 'admin_user'));

                return $this->redirectToRoute('app_modules_main_ui_dashboard_admin');
            }
        }

        return $this->render('user/changePassword.html.twig', []);
    }
}
