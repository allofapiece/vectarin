<?php

namespace App\Controller;


use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\QuizType;
use App\Service\DataChecker\QuizDataChecker;
use App\Service\QuestionService;
use App\Service\QuizCreateFormHandler;
use App\Service\QuizCreator;
use App\Service\QuizService;
use App\Service\QuizUpdateFormHandler;
use App\Service\QuizUpdater;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends Controller
{
    private $quizCreateFormHandler;

    private $quizUpdateFormHandler;

    private $quizService;

    private $quizDataChecker;

    private $updater;

    public function __construct(
        QuizCreateFormHandler $quizCreateFormHandler,
        QuizUpdateFormHandler $quizUpdateFormHandler,
        QuizService $quizService,
        QuizUpdater $updater,
        QuizDataChecker $quizDataChecker
    )
    {
        $this->quizCreateFormHandler = $quizCreateFormHandler;
        $this->quizUpdateFormHandler = $quizUpdateFormHandler;
        $this->quizService = $quizService;
        $this->quizDataChecker = $quizDataChecker;
        $this->updater = $updater;
    }

    /**
     * @Route("/admin/quiz/create", name="quiz.create")
     *
     * @param Request $request
     * @return Response
     */
    public function createQuiz(Request $request)
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
     * @Route("/quiz/own",name="quiz.own")
     * @param Request $request
     * @param QuestionService $questionService
     * @return Response
     */
    public function showOwnQuizzes(Request $request, QuestionService $questionService)
    {
        $result = $questionService->getQuestionsByText($request->get('q'));

        return new JsonResponse($result);
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