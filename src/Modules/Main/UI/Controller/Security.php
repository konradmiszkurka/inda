<?php

declare(strict_types=1);

namespace App\Modules\Main\UI\Controller;

use App\Lib\Application\Notification\NotificationNames;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class Security extends AbstractController
{
    public const PERMISSION_DENIED_REDIRECT = self::class . '::permissionDeniedRedirect';
    public const PERMISSION_DENIED_VIEW = self::class . '::permissionDeniedView';

    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(
        TranslatorInterface $translator,
        RequestStack $requestStack
    ) {
        $this->translator = $translator;
        $this->requestStack = $requestStack;
    }

    public function permissionDeniedRedirect(): RedirectResponse
    {
        $this->addFlash(NotificationNames::DANGER, $this->translator->trans('Permission denied.', [], 'admin_main'));

        return $this->redirect($this->requestStack->getMasterRequest()->headers->get('referer'));
    }

    public function permissionDeniedView(): Response
    {
        return $this->render(
            'main/permissionDenied.html.twig',
            []
        );
    }
}
