<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Workshop;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start_at', DateTimeType::class, [
                'label' => false,
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
