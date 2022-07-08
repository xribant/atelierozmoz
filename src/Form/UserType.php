<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'required' => true,
            'label' => false,
            'attr' => [
                'placeholder' => 'E-Mail',
                'class' => 'form-control'
            ]
        ])
        ->add('firstName', TextType::class, [
            'required' => true,
            'label' => false,
            'attr' => [
                'placeholder' => 'Prénom',
                'class' => 'form-control'
            ]
        ])
        ->add('lastName', TextType::class, [
            'required' => true,
            'label' => false,
            'attr' => [
                'placeholder' => 'Nom',
                'class' => 'form-control'
            ]
        ])
        ->add('password', RepeatedType::class, [
            'label' => false,
            'required' => true,
            'type' => PasswordType::class,
            'invalid_message' => 'Les mot de passe saisis doivent correspondre',
            'attr' => [
                'class' => 'form-control form-control-lg form-control-a'
            ],
            'first_options' => [
                'label' => false,
            ],
            'second_options' => [
                'label' => 'Confirmer le mot de passe',
            ]
        ])
        ->add('Roles', ChoiceType::class, [
            'label' => false,
            'required' => true,
            'expanded' => false,
            'multiple' => false,
            'choices' => [
                'User' => 'ROLE_USER',
                'Admin' => 'ROLE_ADMIN'
            ],
            'attr' => [
                'class' => 'form-control'
            ]
        ])
        ->add('imageFile', VichImageType::class, [
            'required' => false,
            'label' => false,
            'download_link' => false,
            'download_uri' => false,
            'delete_label' => 'Supprimer l\'image courante',
            'attr' => [
                'class' => 'imageField'
            ]
        ])
        ;

        // Data transformer
        $builder->get('Roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                     // transform the array to a string
                     return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                     // transform the string back to an array
                     return [$rolesString];
                }
        ));
 

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
