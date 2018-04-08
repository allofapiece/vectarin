<?php

declare(strict_types=1);

namespace App\Service\Validator;

use App\Entity\Quiz;

class QuizCreateFormValidator
{
    protected $isValid;

    protected $errorMessages;

    /**
     * QuizCreateFormValidator constructor.
     * @param bool $isValid
     * @param array $errorMessages
     */
    public function __construct(
        bool $isValid = true,
        array $errorMessages = []
    )
    {
        $this->isValid = $isValid;
        $this->errorMessages = $errorMessages;
    }

    /**
     * @param Quiz $data
     * @return bool
     */
    public function validate(Quiz $data): bool
    {

        return $this->isValid;
    }

    /**
     * @return bool
     */
    public function getIsValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @param bool $isValid
     * @return void
     */
    public function setIsValid(bool $isValid): void
    {
        $this->isValid = $isValid;
    }

    /**
     * @return array||null
     */
    public function getErrorMessages(): ?array
    {
        return $this->errorMessages;
    }

    /**
     * @param string $message
     * @return void
     */
    public function addMessage(string $message): void
    {
        $this->errorMessages[] = $message;
    }

    /**
     * @param string $errorMessages
     */
    public function setErrorMessages(string $errorMessages): void
    {
        $this->errorMessages = $errorMessages;
    }


}