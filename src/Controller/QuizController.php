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
     * @Route("/admin/quiz/create", name="quiz.create")
     * @return Response
     */
    public function createQuiz()
    {
        return new Response();
    }

    /**
     * @Route("/admin/quiz/update/{id}", name="quiz.update")
     *
     * @param int $id
     * @return Response
     */
    public function updateQuiz(int $id)
    {
        return new Response();
    }

    /**
     * @Route("/admin/quiz/delete/{id}", name="quiz.delete")
     * @return Response
     */
    public function deleteQuiz(int $id)
    {
        $quiz = $this->quizService->find($id);

        $this->quizService->delete($quiz);

        $this->quizService->commit($quiz);

        return $this->redirectToRoute('quiz.show');
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
     * @Route("/quiz/show",name="quiz.show")
     */
    public function showAllQuiz()
    {
        $quizzes = $this->quizService->findAll();

        return $this->render('quizzes/quizzes.html.twig', [
            'quizzes' => $quizzes
        ]);
    }

    /**
     * @Route("/quiz/user",name="quiz.user.show")
     */
    public function showUserQuizzes()
    {

    }
}