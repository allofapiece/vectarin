<?php

declare(strict_types=1);

namespace App\Form\Question;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class QuestionType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, [
                'label' => 'Текст вопроса',
                'attr' => [
                    'placeholder' => 'Вопрос'
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
            ->add('answers', CollectionType::class, [
                'label' => false,
                'by_reference' => false,
                'allow_add' => true,
                'entry_type' => AnswerType::class,
                // these options are passed to each "email" type
                'entry_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => 'form-cotrol',
                    ],
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить вопрос'
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}