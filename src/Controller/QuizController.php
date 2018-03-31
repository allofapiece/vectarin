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

class QuizController extends Controller
{
    /**
     * @Route("/quizzes")
     * @Route("/", name="quizzes")
     */
    public function quizzesAction(){
        return $this->render('quizzes/quizzes.html.twig');
    }

    /**
     * @Route("/myquizzes", name="myquizzes")
     */
    public function myQuizzesAction(){
        return $this->render('quizzes/myquizzes.html.twig');
    }
}