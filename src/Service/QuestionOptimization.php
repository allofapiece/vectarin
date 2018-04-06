<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 06.04.2018
 * Time: 11:12
 */

namespace App\Service;


use App\Entity\Question;

class QuestionOptimization
{
    private $question;

    public function optimizeQuestionText(Question $question){
        $questionText = $question->getText();

        trim($questionText);

        $question->setText($questionText);
    }

    public function addQuestionCharacterIfNotExist(Question $question)
    {
        $questionText = $question->getText();

        if(substr($questionText, strlen($questionText)-1) != '?'){

            $questionText .= '?';

            $question->setText($questionText);
        }
    }

    public function deleteQuestionCharacterIfExist(Question $question)
    {
        $questionText = $question->getText();

        if(substr($questionText, strlen($questionText)-1) == '?'){

            $questionText = substr($questionText, 0, strlen($questionText)-1);

            $question->setText($questionText);

        }
    }
}