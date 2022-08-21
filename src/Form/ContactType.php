<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'required' => true,
            'label' => false,
            'attr' => [
                'class' => 'mt-3',
                'placeholder' => 'Nom',
                'size' => 30,
            ],
            'constraints' => [
                new NotBlank(message: 'Veuillez entrer votre nom')
            ],
        ])
        ->add('email', EmailType::class, [
            'required' => true,
            'label' => false,
            'attr' => [
                'placeholder' => 'E-Mail',
                'size' => 30,
            ],
            'constraints' => [
                new NotBlank(message: 'Veuillez entrer votre e-mail'),
                new Email(message: 'Veuillez entrer une adresse e-mail valide')
            ],
        ])
        ->add('message', TextareaType::class, [
            'required' => true,
            'label' => false,
            'attr' => [
                'placeholder' => 'Votre message',
                'cols' => 30,
                'rows' => 10
            ],
            'constraints' => [
                new NotBlank(message: 'Veuillez taper un message')
            ],
        ])
        ->add('captcha', Recaptcha3Type::class, [
            'constraints' => new Recaptcha3(),
            'action_name' => 'app_home'
        ])
    ;
    }
}
