<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 05.04.2018
 * Time: 17:41
 */

namespace App\Service\DataChecker;

use App\Entity\Question;

class QuestionDataChecker extends DataCheckerService
{

    /**
     * @param Question $question
     * @return bool
     */
    public function checkData(Question $question): bool
    {
        $this->setIsError(false);

        $answers = $question->getAnswers();

        if($answers->count() <= 1){
            $this->setIsError(true);
            $this->addMessage('Ответов должно быть больше 1');
        }

        $isCorrectAmount = 0;

        foreach($answers as $answer){
            if($answer->getIsCorrect()){
                $isCorrectAmount++;
            }
        }

        if($isCorrectAmount != 1){
            $this->setIsError(true);
            $this->addMessage('Должен быть 1 правильный ответ');
        }

        return $this->getIsError();
    }

}