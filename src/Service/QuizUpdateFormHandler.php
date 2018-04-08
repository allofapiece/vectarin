<?php

declare(strict_types=1);

namespace App\Service;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class QuizUpdateFormHandler extends FormHandler
{
    private $updater;

    private $validator;

    /**
     * QuizCreateFormHandler constructor.
     * @param bool $isFormValid
     * @param array $formErrorMessages
     * @param QuizUpdater $updater
     * @param QuizUpdateFormValidator $validator
     */
    public function __construct(
        bool $isFormValid = true,
        array $formErrorMessages = [],
        QuizUpdater $updater,
        QuizUpdateFormValidator $validator
    )
    {
        $this->isFormValid = $isFormValid;
        $this->formErrorMessages = $formErrorMessages;
        $this->updater = $updater;
        $this->validator = $validator;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @param array $parameters
     * @return bool
     */
    public function handle(FormInterface $form, Request $request, array $parameters): bool
    {
        $originalQuestions = new ArrayCollection();
        foreach ($parameters['entity']->getQuestions() as $question) {
            $originalQuestions->add($question);
        }

        if($request->isMethod('POST')){
            $form->submit($request->request->get($form->getName()));
            $data = $form->getData();

            if($this->validator->validate($data)){
                $this->updater->update($data, $originalQuestions);
            } else {
                $this->setIsFormValid(false);
                $this->setFormErrorMessages($this->validator->getErrorMessages());
            }

            return $this->getIsFormValid();
        }

        return false;
    }
}