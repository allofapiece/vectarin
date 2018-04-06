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
                        'min' => 1,
                        'max' => 50,
                        'minMessage' => 'Число символов не должно быть меньше {{ limit }}',
                        'maxMessage' => 'Число символов не должно быть больше {{ limit }}'
                    ])
                ],
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ответ',
                    'class' => 'form-control'
                ]
            ])
            ->add('is_correct', RadioType::class, [
                'label' => false,
                'attr' => [
                    'name' => 'answer',
                    'checked' => false
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Answer::class
        ]);
    }
}