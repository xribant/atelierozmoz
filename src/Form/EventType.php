<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Workshop;
use App\Entity\WorkshopLocation;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start_at', DateTimeType::class, [
                'label' => false,
                'required'=> true,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy HH:mm',
                'model_timezone' => 'Europe/Brussels',
                'attr' => [
                    'class' => 'datetimepicker',
                    'placeholder' => 'Début'
                ]
            ])
            ->add('stop_at', DateTimeType::class, [
                'label' => false,
                'required'=> true,
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy HH:mm',
                'model_timezone' => 'Europe/Brussels',
                'attr' => [
                    'class' => 'datetimepicker',
                    'placeholder' => 'Fin'
                ],
                'constraints' => [
                    new Constraints\Callback(function($object, ExecutionContextInterface $context) {
                        $start = $context->getRoot()->getData()->getStartAt();
                        $stop = $object;

                        if (is_a($start, \DateTime::class) && is_a($stop, \DateTime::class)) {
                            if ($stop->format('U') - $start->format('U') < 0) {
                                $context
                                    ->buildViolation('La date de fin doit être postérieure à la date de début')
                                    ->addViolation();
                            }
                        }
                    })
                ]
            ])
            ->add('workshop', EntityType::class, [
                'class' => Workshop::class,
                'choice_label' => 'title',
                'required' => 'true',
                'label' => false,
                'placeholder' => '',
                'attr' => [
                    'class' => 'btn btn-block dropdown-toggle'
                ]
            ])
            ->add('location', EntityType::class, [
                'class' => WorkshopLocation::class,
                'choice_label' => 'name',
                'required' => 'true',
                'label' => false,
                'placeholder' => '',
                'attr' => [
                    'class' => 'btn btn-block dropdown-toggle'
                ]
            ])
            ->add('active', ChoiceType::class, [
                'label' => false,
                'multiple' => false,
                'expanded' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
            ])
            ->add('title', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Intitulé',
                ],
            ])
            ->add('description',CKEditorType::class, [
                'required' => true,
                'label' => false,
                'config' => [
                    'toolbar' => 'standard'
                ],
                'attr' => [
                    'placeholder' => 'Description',
                    'class' => 'form-control'
                ]
            ])
            ->add('duration', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Durée',
                ],
            ])
            ->add('price', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Prix',
                ],
            ])
            ->add('soldout', ChoiceType::class, [
                'label' => false,
                'multiple' => false,
                'expanded' => false,
                'attr' => [
                    'class' => 'form-control'
                ],
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
