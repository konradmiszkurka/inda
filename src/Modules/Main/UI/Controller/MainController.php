<?php

declare(strict_types=1);

namespace App\Modules\Main\UI\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MainController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function main(): RedirectResponse
    {
        return $this->redirectToRoute('fos_user_security_login');
    }

}
