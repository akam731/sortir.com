<?php

namespace App\Form;

use App\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'constraints' => [
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'Le nom ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'maxlength' => 100,
                ]

                ])
            ->add('zipCode', IntegerType::class, [
                        'constraints' => [
                        new Length([
                            'max' => 5,
                            'maxMessage' => 'Le code ne doit pas dépasser {{ limit }} caractères.',
                        ]),
                    ],
                'attr' => [
                    'maxlength' => 5,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => City::class,
        ]);
    }
}
