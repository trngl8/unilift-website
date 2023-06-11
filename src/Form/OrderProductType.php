<?php

namespace App\Form;

use App\Model\OrderProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{EmailType, TextareaType, TextType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' =>'form.label.name',
            ])
            ->add('phone', TextType::class, [
                'label' =>'form.label.phone',
            ])
            ->add('email', EmailType::class, [
                'label' =>'form.label.email',
            ])
            ->add('location', TextType::class, [
                'label' =>'form.label.location',
            ])
            ->add('description', TextareaType::class, [
                'label' =>'form.label.details',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderProduct::class,
        ]);
    }
}
