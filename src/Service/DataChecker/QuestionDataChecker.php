<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 05.04.2018
 * Time: 17:41
 */

namespace App\Service\DataChecker;

use App\Entity\Question;
use App\Service\QuestionService;

class QuestionDataChecker extends DataCheckerService
{

    private $questionService;

    public function __construct(QuestionService $questionService)
    {
        parent::__construct();

        $this->questionService = $questionService;
    }

    /**
     * @param Question $question
     * @param QuestionService $questionService
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

        /*foreach($answers as $outerAnswer){
            $outerAnswerText = strtolower($outerAnswer->getText());

            foreach($answers as $innerAnswer){
                if(strtolower($innerAnswer->getText()) == $outerAnswerText){
                    $this->setIsError(true);
                    $this->addMessage('Не должно быть одинаковых ответов.');

                    break(2);
                }
            }
        }*/

        $questions = $this->questionService->findAll();

        foreach($questions as $quest){
            if(
                strtolower($quest->getText()) == strtolower($question->getText()) &&
                $quest->getId() != $question->getId()
            ){
                $this->setIsError(true);
                $this->addMessage('Вопрос с таким текстом уже существует.');

                break;
            }
        }

        return $this->getIsError();
    }

}