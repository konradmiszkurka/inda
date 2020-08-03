<?php
declare(strict_types=1);

namespace App\Modules\Main\UI\Controller;

use App\Modules\User\Domain\User\Entity\UserEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard")
     */
    public function admin(): Response
    {
        /** @var UserEntity $user */
        $user = $this->getUser();

        if ($user->isPasswordChangeRequired()) {
            return $this->redirectToRoute('app_modules_user_ui_account_changepassword');
        }

        return $this->render(
            'dashboard/admin.html.twig'
        );
    }
}
