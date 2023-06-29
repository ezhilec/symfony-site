<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('reference_number')
            ->add('category', EntityType::class, [
                'class' => ProductCategory::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a category',
            ])
            ->add('images', FileType::class, [
                'label' => 'Add Images',
                'mapped' => false,
                'multiple' => true,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'cascade_validation' => true,
        ]);
    }
}
