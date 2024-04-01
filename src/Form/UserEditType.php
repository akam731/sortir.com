<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Flex\Options;

class UserEditType extends AbstractType
{
    private $passwordHasher;
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
                        'pattern' => '/^(06|07)\d{8}$/',
                        'message' => 'Ce champ doit être un numéro de téléphone valide.',
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
                //Déjà le but c'était de modifier ça è_é
                'required' => false,
                // C'est ça là, cette putain de ligne de merde qui me les brisent à 7h du mat
                // à pas exister quand on en a besoin ! GNIIIIIIIIIII >.<"
                'mapped' => false,
                //Bref, ça marche maintenant è_é

                'first_options'  => ['label' => 'Nouveau mot de passe', 'empty_data' => ''],
                'second_options' => ['label' => 'Confirmation du mot de passe', 'empty_data' => ''],
                'constraints' => [
                    //OK, avec ça y'a moyen que ça marche, de ne pas remplir le champs mdp.
                    new Assert\Callback([
                        'callback' => function ($value, ExecutionContextInterface $context) use ($options) {

                            if (null === $value || '' === $value) {
                                return;
                            }

                            // On récupère l'utilisateur ET le service d'encodage de mot de passe, sinon marche pas
                            //je crois même que dans le fond c'était en grande partie ça le souci
                            $user = $context->getRoot()->getData();
                            $encodedPassword = $this->passwordHasher->hashPassword($user, $value);

                            if ($user->getPassword() === $encodedPassword) {
                                $context->buildViolation("Le nouveau mot de passe doit être différent de l'ancien.")
                                    ->atPath('password')
                                    ->addViolation();
                            }
                        },
                    ]),
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'password_hasher' => null,
            'allow_extra_fields' => true,
        ]);

        $resolver->setAllowedTypes('password_hasher', UserPasswordHasherInterface::class);

        $resolver->setRequired('password_hasher');

        $resolver->setNormalizer('password_hasher', function (OptionsResolver $options, $value) {
            $this->passwordHasher = $value;

            return $options;
        });
    }
}
