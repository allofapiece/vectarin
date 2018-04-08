<?php

namespace App\Form;


use App\Entity\Quiz;
use App\Form\Question\QuestionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Название викторины',
                'attr' => [
                    'placeholder' => 'Название'
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Число символов не должно быть меньше {{ limit }}',
                        'maxMessage' => 'Число символов не должно быть больше {{ limit }}'
                    ]),
                    new NotBlank([
                        'message' => 'Поле не должно быть пустым.'
                    ])
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Описание викторины',
                'attr' => [
                    'placeholder' => 'Описание'
                ],
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Число символов не должно быть меньше {{ limit }}',
                        'maxMessage' => 'Число символов не должно быть больше {{ limit }}'
                    ])
                ]
            ])
            ->add('questions', CollectionType::class, array(
                'label' => false,
                'by_reference' => false,
                'allow_add' => true,
                'entry_type' => QuestionType::class,
                'entry_options' => array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-cotrol',
                    ),

                ),

            ))
            ->add('save', SubmitType::class, array('label' => 'Сохранить викторину'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}