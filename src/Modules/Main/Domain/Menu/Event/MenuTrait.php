<?php

declare(strict_types=1);

namespace App\Modules\Main\Domain\Menu\Event;

use App\Modules\Attachment\Application\Voter\AttachmentVoter;
use App\Modules\Attachment\Application\Voter\FileVoter;
use App\Modules\Attachment\Application\Voter\PhotoVoter;
use App\Modules\Main\Application\Voter\TranslationVoter;
use App\Modules\Product\Application\Voter\ProductVoter;
use App\Modules\User\Application\Voter\UserVoter;

trait MenuTrait
{
    public function getNavigation()
    {
        return array_merge(
            [
                $this->dashboard()
            ],
            $this->inda()
        );
    }

    public function getAdmin()
    {
        return [
//            $this->user(),
        ];
    }

    private function dashboard(): array
    {
        return [
            'label' => 'Dashboard',
            'route' => 'app_modules_main_ui_dashboard_admin',
            'icon' => 'zmdi zmdi-home',
            'child' => [
                [
                    'label' => 'Main',
                    'route' => 'app_modules_main_ui_main_main',
                ],
                [
                    'label' => 'Language',
                    'route' => '',
                    'child' => [
                        [
                            'label' => 'Change',
                            'route' => 'app_modules_language_ui_language_change',
                            'params' => ['locale' => '__request__'],
                        ],
                    ],
                ],
            ],
        ];
    }

    private function inda(): array
    {
        return [
            [
                'label' => 'Products',
                'route' => 'app_modules_product_ui_admin_product_lists',
                'icon' => 'zmdi zmdi-gamepad',
                'granted' => ProductVoter::LISTS,
                'child' => [
                    [
                        'label' => 'Create',
                        'route' => 'app_modules_product_ui_admin_product_create',
                    ],
                ],
            ],
        ];
    }

    private function user(): array
    {
        return [
            'label' => 'Users',
            'route' => 'app_modules_user_ui_admin_user_lists',
            'icon' => 'zmdi zmdi-accounts-alt',
            'granted' => UserVoter::LISTS,
            'child' => [
                [
                    'label' => 'Login',
                    'route' => 'fos_user_security_login',
                    'child' => [
                        [
                            'label' => 'Login - check',
                            'route' => 'fos_user_security_check',
                        ],
                    ],
                ],
                [
                    'label' => 'Profile',
                    'route' => 'app_modules_user_ui_admin_account_account',
                    'child' => [
                        [
                            'label' => 'Logout',
                            'route' => 'fos_user_security_logout',
                        ],
                    ],
                ],
                [
                    'label' => 'Create',
                    'route' => 'app_modules_user_ui_admin_user_create',
                ],
                [
                    'label' => 'Active',
                    'route' => 'app_modules_user_ui_admin_user_active',
                    'params' => ['id' => '__request__'],
                ],
                [
                    'label' => 'Deactivate',
                    'route' => 'app_modules_user_ui_admin_user_deactivate',
                    'params' => ['id' => '__request__'],
                ],
                [
                    'label' => 'Remove',
                    'route' => 'app_modules_user_ui_admin_user_remove',
                    'params' => ['id' => '__request__'],
                ],
                [
                    'label' => 'User',
                    'route' => '',
                    'child' => [
                        [
                            'label' => 'Edit',
                            'route' => 'app_modules_user_ui_admin_user_edit',
                            'params' => ['id' => '__request__'],
                        ],
                    ],
                ],
            ],
        ];
    }
}
