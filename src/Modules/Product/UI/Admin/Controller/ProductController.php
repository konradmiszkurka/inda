<?php

declare(strict_types=1);

namespace App\Modules\Product\UI\Admin\Controller;

use App\Lib\Application\Notification\NotificationNames;
use App\Lib\Domain\Transform\ObjectTransform;
use App\Modules\Product\Application\Voter\ProductVoter;
use App\Modules\Product\UI\Admin\Request\FormProductData;
use App\Modules\Product\Application\ProductApplicationService;
use App\Modules\Product\Domain\Product\Entity\ProductEntity;
use App\Modules\Product\UI\Admin\Form\ProductType;
use App\Modules\Main\UI\Controller\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/admin")
 */
final class ProductController extends AbstractController
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
     * @var ProductApplicationService
     */
    private $application;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TranslatorInterface $translator,
        ProductApplicationService $application
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->translator = $translator;
        $this->application = $application;
    }

    /**
     * @Route("/new-product")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request): Response
    {
        if (!$this->authorizationChecker->isGranted(ProductVoter::CREATE)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $data = (new FormProductData());

        $form = $this
            ->createForm(ProductType::class, $data)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->application->createProduct($data);

            if (true === $result->isSuccessful()) {
                $this->addFlash(
                    NotificationNames::SUCCESS,
                    $this->translator->trans('Added Product', [], 'admin_product')
                );

                return $this->redirectToRoute(
                    'app_modules_game_ui_admin_product_edit',
                    ['id' => $result->getIdentifier()]
                );
            }
        }

        return $this->render(
            '@Oculux/Pages/form.html.twig',
            [
                'titleBox' => $this->translator->trans('Product form', [], 'admin_product'),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit")
     * @param Request $request
     * @param ProductEntity $entity
     * @return Response
     */
    public function edit(Request $request, ProductEntity $entity): Response
    {
        if (!$this->authorizationChecker->isGranted(ProductVoter::SHOW, $entity)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        /** @var FormProductData $data */
        $data = (new ObjectTransform(FormProductData::class))->setData($entity)->getResult();

        $form = $this
            ->createForm(ProductType::class, $data)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->authorizationChecker->isGranted(ProductVoter::EDIT, $entity)) {
                return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
            }

            $result = $this->application->updateProduct($entity, $data);

            if (true === $result->isSuccessful()) {
                $this->addFlash(
                    NotificationNames::SUCCESS,
                    $this->translator->trans(
                        'Edited Product in room `%name%`',
                        ['%name%' => $entity->getRoom()->getName(),],
                        'admin_product'
                    )
                );

                return $this->redirectToRoute('app_modules_product_ui_admin_product_lists');
            }
        }

        return $this->render(
            '@Oculux/Pages/form.html.twig',
            [
                'titleBox' => $this->translator->trans('Product form', [], 'admin_product'),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/lists")
     * @param Request $request
     * @return Response
     */
    public function lists(Request $request): Response
    {
        if (!$this->authorizationChecker->isGranted(ProductVoter::LISTS)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $table = $this->application->getProductsListDataTable()
            ->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render(
            'product/products.html.twig',
            [
                'titleBox' => $this->translator->trans('Products list', [], 'admin_product'),
                'datatable' => $table,
                'paths' => [
                    'create' => 'product_modules_game_ui_admin_product_create',
                ],
            ]
        );
    }
}
