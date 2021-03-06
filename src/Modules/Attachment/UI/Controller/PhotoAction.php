<?php
declare(strict_types=1);

namespace App\Modules\Attachment\UI\Controller;

use App\Lib\Domain\Result\ResultInterface;
use App\Modules\Attachment\Application\Voter\PhotoVoter;
use App\Modules\Main\UI\Controller\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class PhotoAction extends AbstractController
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function photoSave(ResultInterface $result): Response
    {
        if (true === $result->isSuccessful()) {
            return new Response('', 200);
        }

        return new Response('', 500);
    }

    public function photoSaveInfo(ResultInterface $result): bool
    {
        if (true === $result->isSuccessful()) {
            return true;
        }

        return false;
    }

    /**
     * @param array $paths
     * @return Response
     */
    public function photoAdd(array $paths): Response
    {
        if (!$this->authorizationChecker->isGranted(PhotoVoter::CREATE)) {
            return $this->forward(Security::PERMISSION_DENIED_VIEW);
        }

        return $this->render(
            'attachment/photo/add.html.twig',
            [
                'paths' => $paths,
            ]
        );
    }
}
