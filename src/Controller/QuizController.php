<?php

namespace App\Controller;


use App\Service\QuizService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends Controller
{

    private $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }


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
     * @return Response
     */
    public function showOwnQuizzes()
    {
        return $this->render('quizzes/myquizzes.html.twig');
    }

    /**
     * @Route("/quiz/show",name="quizzes.show")
     */
    public function showAllQuiz()
    {
        $quizzes = $this->quizService->findAll();

        return $this->render('quizzes/quizzes.html.twig', [
            'quizzes' => $quizzes
        ]);
    }

    /**
     * @Route("/quiz/user",name="quizzes.user.show")
     */
    public function showUserQuizzes()
    {

    }
}