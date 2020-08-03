<?php

declare(strict_types=1);

namespace App\Modules\Product\UI\Admin\Controller;

use App\Lib\Application\Notification\NotificationNames;
use App\Lib\Domain\Params\Pagination;
use App\Lib\Domain\Transform\ObjectTransform;
use App\Modules\Product\Application\PriceApplicationService;
use App\Modules\Product\Application\Voter\ProductVoter;
use App\Modules\Product\Domain\Product\Interfaces\ProductRepositoryInterface;
use App\Modules\Product\UI\Admin\Request\FormProductData;
use App\Modules\Product\Application\ProductApplicationService;
use App\Modules\Product\Domain\Product\Entity\ProductEntity;
use App\Modules\Product\UI\Admin\Form\ProductType;
use App\Modules\Main\UI\Controller\Security;
use App\Modules\Product\UI\Admin\Request\FormProductWithPriceData;
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
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var PriceApplicationService
     */
    private $priceApplicationService;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TranslatorInterface $translator,
        ProductApplicationService $application,
        ProductRepositoryInterface $productRepository,
        PriceApplicationService $priceApplicationService
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->translator = $translator;
        $this->application = $application;
        $this->productRepository = $productRepository;
        $this->priceApplicationService = $priceApplicationService;
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

        $data = (new FormProductWithPriceData());

        $form = $this
            ->createForm(ProductType::class, $data)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $priceCreate = $this->priceApplicationService->createPriceWithCurrency($data->getPrice(), $request->getLocale());

            if ($priceCreate) {
                $result = $this->application->createProduct((new FormProductData())
                ->setPrice($priceCreate)
                ->setName($data->getName())
                ->setDescription($data->getDescription())
                );
            } else {
                $this->addFlash(
                    NotificationNames::DEFAULT,
                    $this->translator->trans('Something went wrong with get price by locale', [], 'admin_product')
                );

                return $this->redirectToRoute(
                    'app_modules_product_ui_admin_product_lists',
                );
            }

            if (true === $result->isSuccessful()) {
                $this->addFlash(
                    NotificationNames::SUCCESS,
                    $this->translator->trans('Added Product', [], 'admin_product')
                );

                return $this->redirectToRoute(
                    'app_modules_product_ui_admin_product_lists'
                );
            }
        }

        return $this->render(
            'product/create.html.twig',
            [
                'titleBox' => $this->translator->trans('Product form', [], 'admin_product'),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/products/lists")
     * @param Request $request
     * @return Response
     */
    public function lists(Request $request): Response
    {
        if (!$this->authorizationChecker->isGranted(ProductVoter::LISTS)) {
            return $this->forward(Security::PERMISSION_DENIED_REDIRECT);
        }

        $products = $this->productRepository->findAll(null,new Pagination(null, 10));

        return $this->render(
            'product/list.html.twig',
            [
                'titleBox' => $this->translator->trans('Products list', [], 'admin_product'),
                'products' => $products,
                'paths' => [
                    'create' => 'product_modules_game_ui_admin_product_create',
                ],
            ]
        );
    }
}
