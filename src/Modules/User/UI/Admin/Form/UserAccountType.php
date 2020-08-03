<?php
declare(strict_types=1);

namespace App\Modules\User\UI\Admin\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstName',
                TextType::class,
                [
                    'label' => 'First name',
                    'required' => true,
                ]
            )
            ->add(
                'lastName',
                TextType::class,
                [
                    'label' => 'Last name',
                    'required' => true,
                ]
            )
            ->add(
                'phone',
                TextType::class,
                [
                    'label' => 'Phone',
                    'required' => false,
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'E-mail',
                    'required' => true,
                ]
            );
        if ($options['showChangePassword']) {
            $builder
                ->add(
                    'password',
                    RepeatedType::class,
                    [
                        'type' => PasswordType::class,
                        'required' => false,
                        'options' => [
                            'attr' => [
                                'autocomplete' => 'new-password',
                            ],
                        ],
                        'first_options' => [
                            'label' => 'Password',
                        ],
                        'second_options' => [
                            'label' => 'Password repeat',
                        ],
                        'invalid_message' => 'Passwords are not equal.',
                    ]
                );
        }
        $builder
            ->add(
                'avatarFileNew',
                FileType::class,
                [
                    'label' => 'Avatar',
                    'required' => false,
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Save',
                    'attr' => ['class' => 'btn btn-primary waves-effect'],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('translation_domain', 'admin_user');
        $resolver->setDefault('showChangePassword', true);
    }
}
