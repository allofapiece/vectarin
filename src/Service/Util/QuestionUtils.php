<?php

declare(strict_types=1);

namespace App\Service\Util;

use App\Entity\Question;

class QuestionUtils
{

    /**
     * @param Question $question
     * @return Question
     */
    public function deleteWhiteSpaces(Question $question)
    {
        $questionText = $question->getText();
        trim($questionText);
        $question->setText($questionText);

        return $question;
    }

    /**
     * @param Question $question
     * @return Question
     */
    public function addQuestionCharacterIfNotExist(Question $question)
    {
        $questionText = $question->getText();

        if(substr($questionText, strlen($questionText)-1) != '?'){
            $questionText .= '?';
            $question->setText($questionText);
        }

        return $question;
    }

    /**
     * @param Question $question
     * @return Question
     */
    public function deleteQuestionCharacterIfExist(Question $question)
    {
        $questionText = $question->getText();

        if(substr($questionText, strlen($questionText)-1) == '?'){
            $questionText = substr($questionText, 0, strlen($questionText)-1);
            $question->setText($questionText);
        }

        return $question;
    }
}