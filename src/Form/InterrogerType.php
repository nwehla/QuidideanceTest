<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Interroger;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class InterrogerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule', TextType::class,[
                'label' => 'Question' ,
                'attr' => ['placeholder' => 'Question'],
                'constraints' => [
                    new Length([
                        'min' => 2 ,
                        'max' => 30
                    ]),
                    new NotBlank(['message' => 'Veuillez remplir ce champ'])
                    ],
                'required' => 'true'
            ])
            ->add('categorie', EntityType::class, [
                // each entry in the array will be an "email" field
                // these options are passed to each "email" type
            // ->add('categorie', EntityType::class, ,[
                'class'=>Categories::class,
                'placeholder'=>'selectionnner une categorie',

                'choice_label'=>'titre',
                'mapped' => true,
                // utiliser un checkbox à choix unique ou multiple
                'multiple'=>true,
                'expanded'=>true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Interroger::class,
        ]);
    }
}
