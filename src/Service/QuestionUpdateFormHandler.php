<?php

declare(strict_types=1);

namespace App\Service;


use App\Service\Util\QuestionUtils;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class QuestionUpdateFormHandler extends FormHandler
{
    private $updater;

    private $validator;

    private $questionUtils;

    /**
     * QuizCreateFormHandler constructor.
     * @param bool $isFormValid
     * @param array $formErrorMessages
     * @param QuestionUpdater $updater
     * @param QuestionUpdateFormValidator $validator
     */
    public function __construct(
        bool $isFormValid = true,
        array $formErrorMessages = [],
        QuestionUpdater $updater,
        QuestionUpdateFormValidator $validator,
        QuestionUtils $questionUtils
    )
    {
        $this->isFormValid = $isFormValid;
        $this->formErrorMessages = $formErrorMessages;
        $this->updater = $updater;
        $this->validator = $validator;
        $this->questionUtils = $questionUtils;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @param array $parameters
     * @return bool
     */
    public function handle(FormInterface $form, Request $request, array $parameters): bool
    {
        $originalAnswers = new ArrayCollection();
        foreach ($parameters['entity']->getAnswers() as $answer) {
            $originalAnswers->add($answer);
        }

        $form->handleRequest($request);

        $this->questionUtils->deleteEmptyAnswers($parameters['entity']);

        if($form->isSubmitted()){

            $data = $form->getData();

            if($form->isValid() && $this->validator->validate($data)){
                $this->updater->update($data, $originalAnswers);
            } else {
                $this->setIsFormValid(false);
                $this->setFormErrorMessages($this->validator->getErrorMessages());
            }

            return $this->getIsFormValid();
        }

        return false;
    }
}