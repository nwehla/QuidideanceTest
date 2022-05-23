<?php

namespace App\Form;

use DateTime;
use App\Entity\Reponse;
use App\Entity\Sondage;
use App\Entity\Interroger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SondageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',TextType::class, [
                'label' => 'Titre du sondage :',
                'attr' => [
                'autofocus' => true,
                'placeholder' => 'Entrez le titre du sondage',
                ],
                ])
            // ->add('question',TextareaType::class, [
            //     'label' => 'Question :',
               
            //     'attr' => [
            //     'placeholder' => 'Entrez la question',
            //     'rows' => 10,
            //     'cols' => 20,
            //     ],
            //     ])
            ->add('description',TextareaType::class, [
                'label' => 'Description du sondange :',
                'required' => false,
                'attr' => [
                'placeholder' => 'Descriptif du sondage',
                'rows' => 10,
                'cols' => 20,
                ],
                ])

            // ->add('interroger', EntityType::class, [
            //     // Label du champ    
            //     'label'  => 'Question',
            //     'placeholder' => 'Sélectionner',
            //     // looks for choices from this entity
            //     'class' => Interroger::class,
            //     // Sur quelle propriete je fais le choix
            //     'choice_label' => 'intitule',
            //     // used to render a select box, check boxes or radios
            //      'multiple' => true,
            //     'expanded' => false,
            //     'required' => true,
            // ])

            // ->add('reponse', EntityType::class, [
            //     // Label du champ    
            //     'label'  => 'Réponse',
            //     'placeholder' => 'Sélectionner',
            //     // looks for choices from this entity
            //     'class' => Reponse::class,
            //     // Sur quelle propriete je fais le choix
            //     'choice_label' => 'intitule',
            //     // used to render a select box, check boxes or radios
            //     // 'multiple' => true,
            //     //'expanded' => true,)
            // ])
                
            ->add('multiple',CheckboxType::class, [
                'label' => 'Plusieurs réponses possibles.',
                'required' => false,
                ])

            ->add('statut',ChoiceType::class, [
                'label' => 'Statut du sondage :',
                'expanded' => true,
               'data'=>$options['statut'],
                'choices' => [
                'Brouillon' => 'Brouillon',
                'Ouvert' => 'Ouvert',
                'Fermé' => 'Fermé',
                ],
                ])
               
            ->add('messagefermeture',TextareaType::class, [
                'label' => 'Message de clôture du sondage :',
                'data' => 'Le sondage a été clôturé.',
                'attr' => [
                'placeholder' => 'Entrez le message de clôture du sondage',
                'rows' => 10,
                'cols' => 20,
                ],
            ]);          


            // ->add('datecreation',DateTime::class, [
            //     'label' => 'Date de création du sondage :'
                
            //     ])
            // ->add('datemiseajour',DateTime::class, [
            //     'label' => 'Date de mise à jour du sondage :'
            // ])
            // ->add('datedefermeture',DateTime::class, [
            //     'label' => 'Date de fermeture du sondage :'
                
            // ]);
        //     ;
        $builder->add('interroger', CollectionType::class, [
            'entry_type' => InterrogerType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sondage::class,
            'statut' => null,

        ]);
    }
}
