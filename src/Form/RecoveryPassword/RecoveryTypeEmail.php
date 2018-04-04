<?php

namespace App\Form\RecoveryPassword;


use App\Entity\RecoveryPassword\CreateEmailRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecoveryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array('label' => 'Введите email'))
//            ->add('code', TextType::class, array('label' => 'Код'))
//            ->add('plainPassword', RepeatedType::class, array(
//                'type' => PasswordType::class,
//                'first_options'  => array('label' => 'Новый пароль'),
//                'second_options' => array('label' => 'Повторите пароль'),
//            ))
            ->add('save', SubmitType::class, array('label' => 'Подтвердить'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CreateEmailRequest::class,
        ]);
    }
}