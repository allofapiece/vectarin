<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 07.04.2018
 * Time: 18:21
 */

namespace App\Service\DataChecker;


use App\Entity\Quiz;

class QuizDataChecker extends DataCheckerService
{
    public function checkData(Quiz $quiz)
    {

        return true;
    }
}