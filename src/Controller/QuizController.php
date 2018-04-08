<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Quiz;
use App\Form\QuizType;
use App\Service\QuizCreateFormHandler;
use App\Service\QuizDeleter;
use App\Service\QuizUpdateFormHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends Controller
{
    private $quizCreateFormHandler;

    private $quizUpdateFormHandler;

    private $quizDeleter;

    public function __construct(
        QuizCreateFormHandler $quizCreateFormHandler,
        QuizUpdateFormHandler $quizUpdateFormHandler,
        QuizDeleter $quizDeleter
    )
    {
        $this->quizCreateFormHandler = $quizCreateFormHandler;
        $this->quizUpdateFormHandler = $quizUpdateFormHandler;
        $this->quizDeleter = $quizDeleter;
    }

    /**
     * @Route("/admin/quiz/create", name="quiz.create")
     *
     * @param Request $request
     * @return Response
     */
    public function createQuiz(Request $request): Response
    {
        $quiz = new Quiz();

        $form = $this->createForm(QuizType::class, $quiz);
        if($this->quizCreateFormHandler->handle($form, $request)){
            return $this->redirectToRoute('quiz.show');
        }

        return $this->render('quizzes/quiz_create.html.twig', [
            'form' => $form->createView(),
            'errors' => $this->quizCreateFormHandler->getFormErrorMessages()
        ]);
    }

    /**
     * @Route("/admin/quiz/update/{id}", name="quiz.update")
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function updateQuiz(int $id, Request $request): Response
    {
        $quiz = $this
            ->getDoctrine()
            ->getRepository(Quiz::class)
            ->find($id);

        if (!$quiz) {
            throw $this->createNotFoundException('Викторины с индексом ' . $id . ' не существует');
        }
        
        $form = $this->createForm(QuizType::class, $quiz);
        if($this->quizUpdateFormHandler->handle($form, $request, ['entity' => $quiz])){
            return $this->redirectToRoute('quiz.show');
        }

        return $this->render('quizzes/quiz_update.html.twig', [
            'form' => $form->createView(),
            'errors' => $this->quizUpdateFormHandler->getFormErrorMessages()
        ]);
    }

    /**
     * @Route("/admin/quiz/delete/{id}", name="quiz.delete")
     *
     * @param int $id
     * @return Response
     */
    public function deleteQuiz(int $id): Response
    {
        if(!$this->quizDeleter->delete($id)){
            throw $this->createNotFoundException('Викторины с индексом ' . $id . ' не существует ');
        }

        return $this->redirectToRoute('quiz.show');
    }

    /**
     * @Route("/quiz/own",name="quiz.own")
     *
     * @param Request $request=
     * @return Response
     */
    public function showOwnQuizzes(Request $request)
    {
        return new Response();
    }

    /**
     * @Route("/quiz/show",name="quiz.show")
     *
     * @return Response
     */
    public function showAllQuizzes(): Response
    {
        $quizzes = $this
            ->getDoctrine()
            ->getRepository(Quiz::class)
            ->findAll();

        return $this->render('quizzes/quizzes.html.twig', [
            'quizzes' => $quizzes
        ]);
    }

}