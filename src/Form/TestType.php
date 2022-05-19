<?php

namespace App\Form;

use App\Entity\Sondage;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('question')
            ->add('description')
            ->add('multiple')
            ->add('statut')
            ->add('messagefermeture')
            ->add('datecreation')
            ->add('datemiseajour')
            ->add('datedefermeture')
        ;

        $builder->get('question')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                $this->setupQuestion(
                    $form->getParent(),
                    $form->getData()
                );
            }
        );
        

    }

    private function setupQuestion(FormInterface $form, ?string $question)
    {
        $form->add('question', TextareaType::class, [
            'placeholder' => 'Question',
            'required' => false,
        ]);
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sondage::class,
        ]);
    }
}
