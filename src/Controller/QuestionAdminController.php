<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionAdminController extends Controller
{
    /**
     * @Route("admin/question/create",name="question.create")
     * @return Response
     */
    public function createQuestion()
    {
        return new Response();
    }

    /**
     * @Route("admin/question/update/{id}",name="question.create")
     * @return Response
     */
    public function updateQuestion()
    {
        return new Response();
    }

    /**
     * @Route("admin/question/delete/{id}",name="question.create")
     * @return Response
     */
    public function deleteQuestion()
    {
        return new Response();
    }

    /**
     * @Route("/admin/question/show",name="question.show")
     * @return Response
     */
    public function showQuestions()
    {
        return $this->render('questions/questions.html.twig');
    }

    /**
     * @Route("/admin/question/show/{id}",name="question.show.id")
     * @return Response
     */
    public function showQuestionById()
    {
        return new Response();
    }
}