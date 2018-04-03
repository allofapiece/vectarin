<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 03.04.2018
 * Time: 17:54
 */

namespace App\Form\Question;

use App\Entity\Question;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextType::class, array('label' => 'Текст вопроса'))
            ->add('answers', CollectionType::class, array(
                'allow_add' => true,
                'prototype' => '<input type="email"
                    id="form_emails___name__"
                    name="form[emails][__name__]"
                    value=""
                />',
                'entry_type' => TextType::class,
                // these options are passed to each "email" type
                'entry_options' => array(
                    //'attr' => array('class' => 'email-box'),
                ),
                ))
            ->add('save', SubmitType::class, array('label' => 'Войти'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}