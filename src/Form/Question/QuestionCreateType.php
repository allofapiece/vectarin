<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 03.04.2018
 * Time: 17:54
 */

namespace App\Form\Question;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class QuestionCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
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
            ->add('answers', CollectionType::class, array(
                'label' => false,
                'by_reference' => false,
                'allow_add' => true,
                'entry_type' => AnswerType::class,
                // these options are passed to each "email" type
                'entry_options' => array(
                    'label' => false,
                    'attr' => array(
                        'class' => 'form-cotrol',
                        ),

                ),

                ))
            ->add('save', SubmitType::class, array('label' => 'Создать вопрос'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}