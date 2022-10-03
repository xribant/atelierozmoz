<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Training;

class QuoteRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer un prénom')
                ],
                'label' => false,
                'attr' => [
                    'placeholder' => false,
                    'class' => 'form-control'
                ]
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer un nom')
                ],
                'label' => false,
                'attr' => [
                    'placeholder' => false,
                    'class' => 'form-control'
                ]
            ])
            ->add('company', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => false,
                    'class' => 'form-control'
                ]
            ])
            ->add('vat', TextType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => false,
                    'class' => 'form-control'
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer une adresse e-mail'),
                    new Email(message: 'Veuillez entrer une adresse e-mail valide')
                ],
                'label' => false,
                'attr' => [
                    'placeholder' => false,
                    'class' => 'form-control'
                ]
            ])
            ->add('phone', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(message: 'Veuillez entrer un n° de téléphone')
                ],
                'label' => false,
                'attr' => [
                    'placeholder' => false,
                    'class' => 'form-control'
                ]
            ])
            ->add('nbr_attendees', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new PositiveOrZero(message: 'Veuillez entrer un nombre')
                ],
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '1'
                ]
            ])
            ->add('message', TextareaType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'cols' => 30,
                    'rows' => 10
                ],
                'constraints' => [
                    new NotBlank(message: 'Veuillez taper un message')
                ],
            ])
            ->add('recaptcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'app_home'
            ])
        ;
    }
}
