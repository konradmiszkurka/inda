<?php

declare(strict_types=1);

namespace App\Modules\Attachment\UI\Controller;

use App\Lib\Application\Notification\NotificationNames;
use App\Modules\Attachment\Application\FileApplicationService;
use App\Modules\Attachment\Application\Voter\FileVoter;
use App\Modules\Attachment\Domain\File\Entity\FileEntity;
use App\Modules\Attachment\Domain\File\Interfaces\FileRepositoryInterface;
use App\Modules\Attachment\UI\Form\FileType;
use App\Modules\Attachment\UI\Request\FormFileData;
use App\Modules\Main\UI\Controller\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType as FileInputType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/attachment/file")
 */
class FileController extends AbstractController
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
     * @var FileApplicationService
     */
    private $application;
    /**
     * @var FileRepositoryInterface
     */
    private $repository;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TranslatorInterface $translator,
        FileApplicationService $application,
        FileRepositoryInterface $repository
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
     */
    public function create(Request $request): Response
    {
        if (!$this->authorizationChecker->isGranted(FileVoter::CREATE)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $data = new FormFileData();
        $form = $this
            ->createForm(FileType::class, $data)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->application->createFile($data);

            if (true === $result->isSuccessful()) {
                $this->addFlash(
                    NotificationNames::SUCCESS,
                    $this->translator->trans('Added file', [], 'admin_attachment')
                );

                return $this->redirectToRoute(
                    'app_modules_attachment_ui_file_edit',
                    ['id' => $result->getIdentifier()]
                );
            }
        }

        return $this->render(
            '@Oculux/Pages/form.html.twig',
            [
                'titleBox' => $this->translator->trans('File form', [], 'admin_attachment'),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit")
     * @param Request $request
     * @param FileEntity $entity
     * @return Response
     */
    public function edit(Request $request, FileEntity $entity): Response
    {
        if (!$this->authorizationChecker->isGranted(FileVoter::SHOW, $entity)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $data = (new FormFileData())
            ->setName($entity->getName())
            ->setDescription($entity->getDescription())
            ->setCategory($entity->getCategory());
        $data->setPath($entity->getPath());
        $data->setMimeType($entity->getMimeType());
        $form = $this
            ->createForm(FileType::class, $data)
            ->add(
                'file',
                FileInputType::class,
                [
                    'label' => 'New file',
                    'required' => false,
                ]
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->authorizationChecker->isGranted(FileVoter::EDIT, $entity)) {
                return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
            }

            $result = $this->application->updateFile($entity, $data);

            if (true === $result->isSuccessful()) {
                $this->addFlash(
                    NotificationNames::SUCCESS,
                    $this->translator->trans(
                        'Edited file `%name%`',
                        ['%name%' => $entity->getName()],
                        'admin_attachment'
                    )
                );

                return $this->redirectToRoute('app_modules_attachment_ui_file_lists');
            }
        }

        return $this->render(
            'attachment/file/formEdit.html.twig',
            [
                'titleBox' => $this->translator->trans('File form', [], 'admin_attachment'),
                'files' => [$entity],
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/remove")
     * @param Request $request
     * @param FileEntity $entity
     * @return Response
     */
    public function remove(Request $request, FileEntity $entity): Response
    {
        if (!$this->authorizationChecker->isGranted(FileVoter::REMOVE, $entity)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $result = $this->application->removeFile($entity);

        if (true === $result->isSuccessful()) {
            $this->addFlash(
                NotificationNames::WARNING,
                $this->translator->trans('Removed file `%name%`', ['%name%' => $entity->getName()], 'admin_attachment')
            );

            if ($request->query->get('redirect')) {
                return $this->redirect($request->query->get('redirect'));
            }

            return $this->redirectToRoute('app_modules_attachment_ui_file_lists');
        }
    }

    /**
     * @Route("/lists")
     * @return Response
     */
    public function lists(): Response
    {
        if (!$this->authorizationChecker->isGranted(FileVoter::LISTS)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        return $this->render(
            'attachment/file/list.html.twig',
            [
                'titleBox' => $this->translator->trans('Files', [], 'admin_attachment'),
                'paths' => [
                    'create' => 'app_modules_attachment_ui_file_create',
                ],
                'items' => $this->repository->findAll()->getAll(),
            ]
        );
    }
}
