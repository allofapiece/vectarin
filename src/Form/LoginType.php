<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 01.04.2018
 * Time: 11:46
 */

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Логин',
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 18,
                        'minMessage' => 'Число символов не должно быть меньше {{ limit }}',
                        'maxMessage' => 'Число символов не должно быть больше {{ limit }}'
                    ]),
                    new NotBlank([
                        'message' => 'Поле не должно быть пустым.'
                    ])
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Пароль',
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 18,
                        'minMessage' => 'Число символов не должно быть меньше {{ limit }}',
                        'maxMessage' => 'Число символов не должно быть больше {{ limit }}'
                    ]),
                    new NotBlank([
                        'message' => 'Поле не должно быть пустым.'
                    ])
                ]
            ])
            ->add('remember_me', CheckboxType::class, [
                'label' => 'Запомнить пароль',
                'mapped' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Войти'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}