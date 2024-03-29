<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Place;
use App\Entity\User;
use phpDocumentor\Reflection\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('startingDate', null, [
                'widget' => 'single_text',
            ])
            ->add('durationTime', null, [
                'widget' => 'single_text',
            ])
            ->add('registrationEnd', null, [
                'widget' => 'single_text',
            ])
            ->add('maxRegistration')
            ->add('eventInformations', TextType::class, [
                'constraints' => [
                    new Length([
//                        todo:passer de 3 à 30
                    'min' => 3,
                    'max' => 1000,
                    'minMessage' => 'La longueur minimale est de {{ limit }} caractères.',
                    'maxMessage' => 'La longueur maximale est de {{ limit }} caractères.',
                    ]),
                ]
            ])
            ->add('place', EntityType::class, [
                'class' => Place::class,
                'choice_label' => 'id',
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
