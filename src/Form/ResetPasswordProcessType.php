<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ResetPasswordProcessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'label' => 'Choisissez votre nouveau mot de passe',
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'required' => true,
                'first_options'  => ['label' => 'Nouveau mot de passe', 'empty_data' => ''],
                'second_options' => ['label' => 'Confirmation du mot de passe', 'empty_data' => ''],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'max' => 255,
                        'minMessage' => 'La longueur minimale est de {{ limit }} caractères.',
                        'maxMessage' => 'La longueur maximale est de {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'minlength' => 6,
                    'maxlength' => 255,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
