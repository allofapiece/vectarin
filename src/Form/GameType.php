<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('result', CollectionType::class, array(
                'label' => false,
                'mapped' => false,
                'by_reference' => false,
                'entry_type' => RadioType::class,
                // these options are passed to each "email" type
                'entry_options' => array(
                    'label' => false,
                    'attr' => array(
                        //'class' => 'form-cotrol',
                    ),
                    'mapped' => false
                ),

            ))
            ->add('save', SubmitType::class, [
                'label' => 'Ответить'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}