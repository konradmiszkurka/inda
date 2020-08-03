<?php

declare(strict_types=1);

namespace App\Modules\Attachment\UI\Controller;

use App\Lib\Application\Notification\NotificationNames;
use App\Modules\Attachment\Application\PhotoApplicationService;
use App\Modules\Attachment\Application\Voter\PhotoVoter;
use App\Modules\Attachment\Domain\Photo\Entity\PhotoEntity;
use App\Modules\Attachment\Domain\Photo\Interfaces\PhotoRepositoryInterface;
use App\Modules\Attachment\UI\Form\PhotoType;
use App\Modules\Attachment\UI\Request\FormPhotoData;
use App\Modules\Main\UI\Controller\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType as FileInputType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/attachment/photo")
 */
class PhotoController extends AbstractController
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
     * @var PhotoApplicationService
     */
    private $application;
    /**
     * @var PhotoRepositoryInterface
     */
    private $repository;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TranslatorInterface $translator,
        PhotoApplicationService $application,
        PhotoRepositoryInterface $repository
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
        if (!$this->authorizationChecker->isGranted(PhotoVoter::CREATE)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $data = new FormPhotoData();
        $form = $this
            ->createForm(PhotoType::class, $data)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->application->createPhoto($data);

            if (true === $result->isSuccessful()) {
                $this->addFlash(
                    NotificationNames::SUCCESS,
                    $this->translator->trans('Added photo', [], 'admin_attachment')
                );

                return $this->redirectToRoute(
                    'app_modules_attachment_ui_photo_edit',
                    ['id' => $result->getIdentifier()]
                );
            }
        }

        return $this->render(
            '@Oculux/Pages/form.html.twig',
            [
                'titleBox' => $this->translator->trans('Photo form', [], 'admin_attachment'),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit")
     * @param Request $request
     * @param PhotoEntity $entity
     * @return Response
     */
    public function edit(Request $request, PhotoEntity $entity): Response
    {
        if (!$this->authorizationChecker->isGranted(PhotoVoter::SHOW, $entity)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $data = (new FormPhotoData())
            ->setName($entity->getName())
            ->setDescription($entity->getDescription());
        $data->setMimeType($entity->getMimeType());
        $data->setPath($entity->getPath());
        $form = $this
            ->createForm(PhotoType::class, $data)
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
            if (!$this->authorizationChecker->isGranted(PhotoVoter::EDIT, $entity)) {
                return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
            }

            $result = $this->application->updatePhoto($entity, $data);

            if (true === $result->isSuccessful()) {
                $this->addFlash(
                    NotificationNames::SUCCESS,
                    $this->translator->trans(
                        'Edited photo `%name%`',
                        ['%name%' => $entity->getName()],
                        'admin_attachment'
                    )
                );

                return $this->redirectToRoute('app_modules_attachment_ui_photo_lists');
            }
        }

        return $this->render(
            'attachment/photo/formEdit.html.twig',
            [
                'titleBox' => $this->translator->trans('Photo form', [], 'admin_attachment'),
                'photos' => [$entity],
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/remove")
     * @param Request $request
     * @param PhotoEntity $entity
     * @return Response
     */
    public function remove(Request $request, PhotoEntity $entity): Response
    {
        if (!$this->authorizationChecker->isGranted(PhotoVoter::REMOVE, $entity)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $result = $this->application->removePhoto($entity);

        if (true === $result->isSuccessful()) {
            $this->addFlash(
                NotificationNames::WARNING,
                $this->translator->trans('Removed photo `%name%`', ['%name%' => $entity->getName()], 'admin_attachment')
            );

            if ($request->query->get('redirect')) {
                return $this->redirect($request->query->get('redirect'));
            }

            return $this->redirectToRoute('app_modules_attachment_ui_photo_lists');
        }
    }

    /**
     * @Route("/lists")
     * @return Response
     */
    public function lists(): Response
    {
        if (!$this->authorizationChecker->isGranted(PhotoVoter::LISTS)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        return $this->render(
            'attachment/photo/list.html.twig',
            [
                'titleBox' => $this->translator->trans('Files', [], 'admin_attachment'),
                'paths' => [
                    'create' => 'app_modules_attachment_ui_photo_create',
                ],
                'items' => $this->repository->findAll()->getAll(),
            ]
        );
    }
}
