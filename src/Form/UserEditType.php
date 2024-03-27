<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class,[
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'La longueur minimale est de {{ limit }} caractères.',
                        'maxMessage' => 'La longueur maximale est de {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    "title" => 'Pseudo',
                    'minlength' => 2,
                    'maxlength' => 100,
                ],
            ])
            ->add('first_name', TextType::class,  [
                'label' => 'Prénom',
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'La longueur minimale est de {{ limit }} caractères.',
                        'maxMessage' => 'La longueur maximale est de {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'minlength' => 2,
                    'maxlength' => 100,
                ],
            ])
            ->add('last_name', TextType::class,  [
                'label' => 'Nom',
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 100,
                        'minMessage' => 'La longueur minimale est de {{ limit }} caractères.',
                        'maxMessage' => 'La longueur maximale est de {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'minlength' => 2,
                    'maxlength' => 100,
                ],
            ])
            ->add('phone', TextType::class,  [
                'label' => 'Téléphone',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Ce champ doit être un nombre.',
                    ]),
                    new Length([
                        'min' => 10,
                        'max' => 10,
                        'minMessage' => 'La longueur minimale est de {{ limit }} caractères.',
                        'maxMessage' => 'La longueur maximale est de {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'minlength' => 10,
                    'maxlength' => 10,
                ],
            ])
            ->add('email', EmailType::class,  [
                'label' => 'Email',
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 180,
                        'minMessage' => 'La longueur minimale est de {{ limit }} caractères.',
                        'maxMessage' => 'La longueur maximale est de {{ limit }} caractères.',
                    ]),
                ],
                'attr' => [
                    'minlength' => 2,
                    'maxlength' => 180,
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'label' => 'Mot de passe',
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmation'],
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
            ->add('imgName', FileType::class,  [
                'label' => 'Ma photo',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'maxSizeMessage' => 'La taille maximale autorisée pour l\'image est de {{ limit }}.',
                    ]),
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image au format JPEG, PNG ou GIF.',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
