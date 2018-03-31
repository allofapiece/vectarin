<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 27.03.2018
 * Time: 20:36
 */

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class QuestionController extends Controller
{
    /**
     * @Route("/questions", name="questions")
     */
    public function quizzesAction(){
        return $this->render('questions/questions.html.twig');
    }
}