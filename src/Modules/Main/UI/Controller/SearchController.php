<?php
declare(strict_types=1);

namespace App\Modules\Main\UI\Controller;

use App\Modules\Main\Application\SearchApplicationService;
use App\Modules\Main\Application\Voter\SearchVoter;
use App\Modules\Main\UI\Controller\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/search")
 */
final class SearchController extends AbstractController
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
     * @var SearchApplicationService
     */
    private $application;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TranslatorInterface $translator,
        SearchApplicationService $application
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->translator = $translator;
        $this->application = $application;
    }

    /**
     * @Route("/")
     * @param Request $request
     * @return Response
     */
    public function main(Request $request): Response
    {
        if (!$this->authorizationChecker->isGranted(SearchVoter::SEARCH)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $results = $this->application->searchTest($request->query->get('query'), (int)$request->query->get('type'));

        return $this->render(
            '@Oculux/Pages/search-result.html.twig',
            [
                'titlePage' => $this->translator->trans('Search results', [], 'admin_search'),
                'results' => $results
            ]
        );
    }
}
