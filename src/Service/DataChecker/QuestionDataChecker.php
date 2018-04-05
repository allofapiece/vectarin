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
    public function checkData(object $question)
    {
        $this->setEntity($question);

        $question->getAnswers();

    }

}