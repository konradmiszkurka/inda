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
            $this->user(),
            $this->translation(),
            $this->attachments(),
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

    private function translation(): array
    {
        return [
            'label' => 'Translations',
            'route' => 'translation_index',
            'icon' => 'zmdi zmdi-flag',
            'granted' => TranslationVoter::MENU,
            'child' => [
                [
                    'label' => 'Show',
                    'route' => 'translation_show',
                    'params' => [
                        'configName' => '__request__',
                        'locale' => '__request__',
                        'domain' => '__request__'
                    ],
                ],
                [
                    'label' => 'Create',
                    'route' => 'translation_create',
                    'params' => [
                        'configName' => '__request__',
                        'locale' => '__request__',
                        'domain' => '__request__'
                    ],
                ],
                [
                    'label' => 'Edit',
                    'route' => 'translation_edit',
                    'params' => [
                        'configName' => '__request__',
                        'locale' => '__request__',
                        'domain' => '__request__'
                    ],
                ],
                [
                    'label' => 'Delete',
                    'route' => 'translation_delete',
                    'params' => [
                        'configName' => '__request__',
                        'locale' => '__request__',
                        'domain' => '__request__'
                    ],
                ],
            ],
        ];
    }

    private function attachments(): array
    {
        return [
            'label' => 'Attachments',
            'route' => '',
            'icon' => 'zmdi zmdi-attachment-alt',
            'granted' => AttachmentVoter::MENU,
            'display' => true,
            'child' => [
                [
                    'label' => 'File',
                    'route' => 'app_modules_attachment_ui_file_lists',
                    'granted' => FileVoter::LISTS,
                    'child' => [
                        [
                            'label' => 'Create',
                            'route' => 'app_modules_attachment_ui_file_create',
                        ],
                        [
                            'label' => 'Remove',
                            'route' => 'app_modules_attachment_ui_file_remove',
                            'params' => ['id' => '__request__'],
                        ],
                        [
                            'label' => 'File',
                            'route' => '',
                            'child' => [
                                [
                                    'label' => 'Edit',
                                    'route' => 'app_modules_attachment_ui_file_edit',
                                    'params' => ['id' => '__request__'],
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'label' => 'Photo',
                    'route' => 'app_modules_attachment_ui_photo_lists',
                    'granted' => PhotoVoter::LISTS,
                    'child' => [
                        [
                            'label' => 'Create',
                            'route' => 'app_modules_attachment_ui_photo_create',
                        ],
                        [
                            'label' => 'Remove',
                            'route' => 'app_modules_attachment_ui_photo_remove',
                            'params' => ['id' => '__request__'],
                        ],
                        [
                            'label' => 'File',
                            'route' => '',
                            'child' => [
                                [
                                    'label' => 'Edit',
                                    'route' => 'app_modules_attachment_ui_photo_edit',
                                    'params' => ['id' => '__request__'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
