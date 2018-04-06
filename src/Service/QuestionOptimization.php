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

    public function optimizeQuestionText(Question $question)
    {
        $questionText = $question->getText();

        trim($questionText);

        if(false === substr($questionText, strlen($questionText))){
            $questionText .= '?';
        }

        $question->setText($questionText);
    }
}