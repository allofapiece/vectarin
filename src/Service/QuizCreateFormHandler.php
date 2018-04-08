<?php

declare(strict_types=1);

namespace App\Service;


use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class QuizCreateFormHandler extends FormHandler
{
    private $creator;

    private $validator;

    /**
     * QuizCreateFormHandler constructor.
     * @param bool $isFormValid
     * @param array $formErrorMessages
     * @param QuizCreator $creator
     * @param QuizCreateFormValidator $validator
     */
    public function __construct(
        bool $isFormValid = true,
        array $formErrorMessages = [],
        QuizCreator $creator,
        QuizCreateFormValidator $validator
    )
    {
        $this->isFormValid = $isFormValid;
        $this->formErrorMessages = $formErrorMessages;
        $this->creator = $creator;
        $this->validator = $validator;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @param array $parameters
     * @return bool
     */
    public function handle(FormInterface $form, Request $request, array $parameters = []): bool
    {
        if($request->isMethod('POST')){

            $form->submit($request->request->get($form->getName()));
            $data = $form->getData();

            if($this->validator->validate($data)){
                $this->creator->create($data);
            } else {
                $this->setIsFormValid(false);
                $this->setFormErrorMessages($this->validator->getErrorMessages());
            }

            return $this->getIsFormValid();
        }

        return false;
    }
}