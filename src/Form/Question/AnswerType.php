<?php

namespace App\Form\Question;


use App\Entity\Answer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 50,
                    ]),
                    new NotBlank([
                        'message' => 'Поле не должно быть пустым'
                    ])
                ],
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ответ'
                ]
            ])
            ->add('is_correct', RadioType::class, [
                'label' => 'Верный ответ'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Answer::class
        ]);
    }
}