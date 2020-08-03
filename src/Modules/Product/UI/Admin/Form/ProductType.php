<?php

declare(strict_types=1);

namespace App\Modules\Product\UI\Admin\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
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
                'price',
                TextType::class,
                [
                    'label' => 'Price',
                    'required' => true,
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'Description',
                    'required' => true,
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
        $resolver->setDefault('translation_domain', 'admin_product');
    }
}
