<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie',
                'required' => true,
            ])
            ->add('startingDate', DateTimeType::class, [
                'label' => 'Date et heure de la sortie',
                'required' => true,
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('registrationEnd', DateTimeType::class, [
                'label' => 'Date limite d\'inscription',
                'required' => true,
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('maxRegistration', IntegerType::class, [
                'label' => 'Nombre de places',
                'data' =>10,
                'required' => true,
            ])
            ->add('durationTime', TimeType::class, [
                'label' => 'Durée',
                'required' => true,
            ])
            ->add('place', EntityType::class, [
                'label' => 'Lieu',
                'class' => Place::class,
                'choice_label' => 'name',
            ])
            ->add('eventInformations', TextareaType::class, [
                'label' => 'Description et infos',
                'required' => true,
                'constraints' => [
                    new Length([
                        //todo : changer 3 en 30 quand on sera sûrs que tout marche
                        'min' => 3,
                        'max' => 1000,
                        'minMessage' => 'La description doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères.'
                    ])
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
