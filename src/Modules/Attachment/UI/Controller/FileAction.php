<?php
declare(strict_types=1);

namespace App\Modules\Attachment\UI\Controller;

use App\Lib\Domain\Result\ResultInterface;
use App\Modules\Attachment\Application\Voter\FileVoter;
use App\Modules\Attachment\Domain\File\Enum\CategoryEnum;
use App\Modules\Main\UI\Controller\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class FileAction extends AbstractController
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

    public function fileSave(ResultInterface $result): Response
    {
        if (true === $result->isSuccessful()) {
            return new Response('', 200);
        }

        return new Response('', 500);
    }

    /**
     * @param array $paths
     * @return Response
     */
    public function fileAdd(array $paths): Response
    {
        if (!$this->authorizationChecker->isGranted(FileVoter::CREATE)) {
            return $this->forward(Security::PERMISSION_DENIED_VIEW);
        }

        return $this->render(
            'attachment/file/add.html.twig',
            [
                'paths' => $paths,
                'categories' => CategoryEnum::getChoices(),
            ]
        );
    }
}
