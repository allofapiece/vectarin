<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionAdminController
{
    /**
     * @Route("admin//question/create",name="question.create")
     * @return Response
     */
    public function createQuestion()
    {

    }

    /**
     * @Route("admin/question/update/{id}",name="question.create")
     * @return Response
     */
    public function updateQuestion()
    {

    }

    /**
     * @Route("admin/question/delete/{id}",name="question.create")
     * @return Response
     */
    public function deleteQuestion()
    {

    }

    /**
     * @Route("/admin/question/show-all",name="question.show-all")
     * @return Response
     */
    public function showQuestions()
    {

    }

    /**
     * @Route("/admin/question/show/{id}",name="question.show")
     * @return Response
     */
    public function showQuestionById()
    {

    }
}