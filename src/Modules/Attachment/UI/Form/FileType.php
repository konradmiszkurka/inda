<?php

declare(strict_types=1);

namespace App\Modules\Attachment\UI\Form;

use App\Modules\Attachment\Domain\File\Enum\CategoryEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType as FileInputType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Name',
                    'required' => true,
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'Description',
                    'required' => true,
                    'attr' => [
                        'rows' => 5,
                    ],
                ]
            )
            ->add(
                'category',
                ChoiceType::class,
                [
                    'label' => 'Category',
                    /** @Ignore */
                    'choices' => CategoryEnum::getChoices(),
                    'expanded' => true,
                    'multiple' => false,
                ]
            )
            ->add(
                'file',
                FileInputType::class,
                [
                    'label' => 'File',
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
        $resolver->setDefault('translation_domain', 'admin_attachment');
    }
}
