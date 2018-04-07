<?php

namespace App\Controller;


use App\Entity\Question;
use App\Service\QuestionService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends Controller
{
    /**
     * @Route("/admin/quiz/control",name="quiz.control")
     * @return Response
     */
    public function quizControl()
    {
        return new Response();
    }

    /**
     * @Route("/admin/quiz/create",name="quiz.create")
     * @return Response
     */
    public function createQuiz()
    {
        return new Response();
    }

    /**
     * @Route("/admin/quiz/update/{id}",name="quiz.update")
     * @return Response
     */
    public function updateQuiz()
    {
        return new Response();
    }

    /**
     * @Route("/admin/quiz/delete/{id}",name="quiz.delete")
     * @return Response
     */
    public function deleteQuiz()
    {
        return new Response();
    }

    /**
     * @Route("/admin/quiz/show/{id}",name="quiz.show.id")
     * @return Response
     */
    public function showQuizById()
    {
        return new Response();
    }

    /**
     * @Route("/quiz/own",name="quiz.own")
     * @param Request $request
     * @param QuestionService $questionService
     * @return Response
     */
    public function showOwnQuizzes(Request $request, QuestionService $questionService)
    {
        $result=$questionService->getQuestionsByText($request->get('q'));

        return new JsonResponse($result);
    }

    /**
     * @Route("/quiz/show",name="quizzes.show")
     */
    public function showAllQuiz()
    {
        return $this->render('quizzes/quizzes.html.twig');
    }

    /**
     * @Route("/quiz/user",name="quizzes.user.show")
     */
    public function showUserQuizzes()
    {

    }
}