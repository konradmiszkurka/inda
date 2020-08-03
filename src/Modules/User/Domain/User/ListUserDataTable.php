<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\User;

use Doctrine\ORM\QueryBuilder;
use App\Lib\Domain\DataTable\ButtonTrait;
use App\Modules\User\Domain\User\Entity\UserEntity;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ListUserDataTable
{
    use ButtonTrait;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;
    /**
     * @var DataTableFactory
     */
    private $dataTableFactory;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        DataTableFactory $dataTableFactory,
        RouterInterface $router,
        TranslatorInterface $translator,
        TokenStorageInterface $tokenStorage
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->dataTableFactory = $dataTableFactory;
        $this->router = $router;
        $this->translator = $translator;
        $this->tokenStorage = $tokenStorage;
    }

    public function create(?array $options = null): DataTable
    {
        if ($options === null) {
            $options = [];
        }
        $options['userId'] = $this->tokenStorage->getToken() ? $this->tokenStorage->getToken()->getUser()->getId() : 0;
        $options['switch'] = ($this->authorizationChecker->isGranted(
                'ROLE_ALLOWED_TO_SWITCH'
            ) && $this->authorizationChecker->isGranted('ROLE_PREVIOUS_ADMIN') === false);

        return $this->dataTableFactory->create()
            ->add(
                'username',
                TextColumn::class,
                [
                    'label' => $this->translator->trans('User name', [], 'admin_user'),
                    'field' => 'e.username'
                ]
            )
            ->add(
                'name',
                TextColumn::class,
                [
                    'label' => $this->translator->trans('Name', [], 'admin_user'),
                    'render' => static function (string $value, UserEntity $userEntity) {
                        return sprintf(
                            '%s %s',
                            $userEntity->getFirstName(),
                            $userEntity->getLastName()
                        );
                    }
                ]
            )
            ->add(
                'email',
                TextColumn::class,
                [
                    'label' => $this->translator->trans('Email', [], 'admin_user'),
                    'field' => 'e.email',
                ]
            )
            ->add(
                'role',
                TextColumn::class,
                [
                    'label' => $this->translator->trans('Role', [], 'admin_user'),
                    'render' => static function (string $value, UserEntity $userEntity) {
                        return $userEntity->getRoleName();
                    }
                ]
            )
            ->add(
                'id',
                TextColumn::class,
                [
                    'label' => $this->translator->trans('Actions', [], 'admin_user'),
                    'render' => function (int $value, UserEntity $userEntity) use ($options) {
                        $btns[] = [
                            'uri' => $this->router->generate(
                                'app_modules_user_ui_admin_user_edit',
                                ['id' => $value]
                            ),
                            'class' => 'btn-warning',
                            'icon' => 'zmdi zmdi-edit',
                        ];
                        if ($userEntity->isEnabled()) {
                            $btns[] = [
                                'uri' => $this->router->generate(
                                    'app_modules_user_ui_admin_user_deactivate',
                                    ['id' => $value]
                                ),
                                'class' => 'btn btn-outline-danger btn-sm',
                                'icon' => 'zmdi zmdi-lock',
                                'confirm' => true,
                            ];
                        } else {
                            $btns[] = [
                                'uri' => $this->router->generate(
                                    'app_modules_user_ui_admin_user_deactivate',
                                    ['id' => $value]
                                ),
                                'class' => 'btn btn-outline-success btn-sm',
                                'icon' => 'zmdi zmdi-lock-open',
                            ];
                        }
                        $btns[] = [
                            'uri' => $this->router->generate(
                                'app_modules_user_ui_admin_user_remove',
                                ['id' => $value]
                            ),
                            'class' => 'btn btn-danger btn-sm',
                            'icon' => 'zmdi zmdi-delete',
                            'confirm' => true,
                        ];
                        if ($options['userId'] !== $value && $options['switch']) {
                            $btns[] = [
                                'uri' => $this->router->generate(
                                    'app_modules_main_ui_dashboard_admin',
                                    ['_switch_user' => $userEntity->getEmail()]
                                ),
                                'class' => 'btn btn-outline-info btn-sm',
                                'icon' => 'zmdi zmdi-eye',
                            ];
                        }

                        return $this->createButtons($btns);
                    }
                ]
            );
    }

    public function adapter(DataTable $table, ?array $options = null): DataTable
    {
        if ($options === null) {
            $options = [];
        }

        return $table->createAdapter(
            ORMAdapter::class,
            [
                'entity' => UserEntity::class,
                'query' => static function (QueryBuilder $builder) use ($options) {
                    $builder
                        ->select('e')
                        ->from(UserEntity::class, 'e');
                },
            ]
        );
    }
}