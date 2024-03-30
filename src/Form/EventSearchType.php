<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\Place;
use App\Entity\User;
use App\Researcher\EventSearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /*
             * c'est un code assez self-explainatory, en le lisant, pensez HTML
             * par exemple première ligne, Campus, donnerai :
             * <label for="campus">Campus :</label>
             * <select id="campus" name="campus">
                  <option value="">Sélectionner un campus</option>
                  <option value="1">Campus A</option>
                  <!-- Autres options de campus -->
             * </select>
             *
             * évitons aussi de se poser trop de question sur entityType, c'est mauvaise magie,
             * suffit de le laisser faire sa popote dans son coin en faisant semblant d'avoir compris ><
             */

            ->add('campus', EntityType::class, [
                'label' => 'Campus :',
                'required' => false,
                'class' => Campus::class,
                'multiple' => false,
            ])

            //dernier détail, en cherchant des infos j'ai trouvé un détail,
            // puis j'ai nommé 'q' pour 'query', par convention. C'est moche mais c'est comme ça è_é

            ->add('q', TextType::class, [
                'label' => 'Le nom de la sortie contient',
                'required' => false,
                'attr' => [
                    'placeholder' => 'search'
                ]
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Entre',
                'html5' => true,
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Et',
                'required' => false,
                'html5' => true,
                'widget' => 'single_text',
            ])
            ->add('isOrganizer', CheckboxType::class, [
                'label' => 'Sorties organisées par mes soins',
                'required' => false,
            ])
            ->add('isRegistered', CheckboxType::class, [
                'label' => 'Sorties incluant ma participation',
                'required' => false,
            ])
            ->add('notRegistered', CheckboxType::class, [
                'label' => 'Sorties auxquelles je peux encore m\'inscrire',
                'required' => false,
            ])
            ->add('pastEvent', CheckboxType::class, [
                'label' => 'Sorties récemment terminées',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EventSearch::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
