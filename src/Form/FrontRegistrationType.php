<?php

namespace App\Form;

use App\Entity\EventRegistration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class FrontRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => false,
                    'class' => 'form-control'
                ]
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => false,
                    'class' => 'form-control'
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => false,
                    'class' => 'form-control'
                ]
            ])
            ->add('born_at', DateType::class, [
                'label' => false,
                'required'=> true,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'model_timezone' => 'Europe/Brussels',
                'attr' => [
                    'class' => 'datepicker',
                    'placeholder' => false
                ]
            ])
            ->add('address', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => false,
                    'class' => 'form-control'
                ]
            ])
            ->add('city', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => false,
                    'class' => 'form-control'
                ]
            ])
            ->add('postal_code', IntegerType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => false,
                    'class' => 'form-control no-spin'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventRegistration::class,
        ]);
    }
}
