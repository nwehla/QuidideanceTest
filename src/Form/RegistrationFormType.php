<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class,[
                'label' => 'Prénom' ,
                'attr' => ['placeholder' => 'Prénom'],
                'constraints' => [
                    new Length([
                        'min' => 2 ,
                        'max' => 30
                    ]),
                    new NotBlank(['message' => 'Veuillez remplir ce champ'])
                    ],
                'required' => 'true'
            ])
            ->add('email', EmailType::class,[
                'label' => 'Email' ,
                'attr' => ['placeholder' => 'Email'],
                'invalid_message' => 'Cet email est déjà pris ' ,
                //'constraints' => [
                    //new UniqueEntity(['fields' => ["email"]])
                    // 'message' => 'Cet email est déjà pris'
                //],
                'required' => 'true'
            ])         
            ->add('password' , RepeatedType::class , [
                'type' => PasswordType::class , 
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identique.' ,
                'label' => 'Votre mot de passe' ,
                'required' => true ,
                'first_options' => [ 'label' => 'Mot de passe' ,
                'attr' => [
                    'placeholder' => ' Veuillez saisir votre mot de passe'
                    ]
                ],                
                'second_options' => [ 'label' => 'Confirmez votre mot de passe' ,
                'attr' => [
                    'placeholder' => 'Confirmez votre mot de passe'
                    ]
                ],
                'constraints' => [
                    new Length([
                        'min' => 8 ,
                        'max' => 30
                    ]),
                        new NotBlank([
                            'message' => 'Veuillez remplir ce champ vide',
                        ]),
                    ],
            ])      
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices'  => [
                  'Admin' => 'ROLE_ADMIN',
                  'SuperAdmin' => 'ROLE_SUPERADMIN',
                ],
            ])
            // // ->add('slug')
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                     // transform the array to a string
                     return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                     // transform the string back to an array
                     return [$rolesString];
                }
        ));
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
