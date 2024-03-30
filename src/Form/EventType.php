<?php

namespace App\Form;

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

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /* Je le dis d'avance, je n'aime pas du tout la manière dont je l'ai refais.
            Nonobstant, suite à nos échanges j'ai refondu ce formulaire qui était encore en cours
            de création pour le faire en 100% Symfony, ce qui inclus les boutons submits et tout et tout
            le but ce serait d'avoir just {{form(ceForm)}} dans twig.
            J'ai seulement pris l'entité 1, et gardé bêtement ce qui est écrit pour le moment.
            */

            ->add('name', TextType::class, [
                'label' => 'Nom de la sortie',
                'required' => true,
            ])
            ->add('startingDate', DateTimeType::class, [
                'widget' => 'datetime_local',
                'label' => 'Date et heure de la sortie',
                'required' => true,
            ])
            ->add('registrationEnd', DateTimeType::class, [
                'widget' => 'datetime_Local',
                'label' => 'Date limite d\'inscription',
                'required' => true,
            ])
            ->add('maxRegistration', IntegerType::class, [
                'label' => 'Nombre de places',
                    //honnêtement je suis pas fan mais flemme de faire attr-placeholder. vu que c'est modifiable plus tard en plus.
                'data' =>10,
                'required' => true,
            ])
            ->add('durationTime', TimeType::class, [
                'label' => 'Durée (en minutes)',
                'data' => 60,
                'required' => true,
            ])
            ->add('place', EntityType::class, [
                'label' => 'Lieu',
                'class' => Place::class,
                'choice_label' => 'name',
            ])
            ->add('eventInformations', TextareaType::class, [
                'label' => 'Description et infos',
                'required' => false,
            ])
            ->add('registerEvent', SubmitType::class, [
                'label'=> 'Enregistrer',
            ])
            ->add('publish', SubmitType::class, [
                'label'=> 'Publier',
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
