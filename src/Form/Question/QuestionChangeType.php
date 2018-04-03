<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 03.04.2018
 * Time: 17:54
 */

namespace App\Form\Question;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionChangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array('label' => 'Логин'))
            ->add('password', PasswordType::class, array('label' => 'Пароль'))
            ->add('remember_me', CheckboxType::class, array(
                'label' => 'Запомнить пароль',
                'mapped' => false))
            ->add('save', SubmitType::class, array('label' => 'Войти'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}