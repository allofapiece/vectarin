<?php

namespace App\Controller;


use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\QuizType;
use App\Service\DataChecker\QuizDataChecker;
use App\Service\QuestionService;
use App\Service\QuizService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends Controller
{

    private $quizService;

    private $quizDataChecker;

    public function __construct(
        QuizService $quizService,
        QuizDataChecker $quizDataChecker
    )
    {
        $this->quizService = $quizService;
        $this->quizDataChecker = $quizDataChecker;
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
    public function createQuiz(Request $request)
    {
        $quiz = new Quiz();

        $form = $this->createForm(QuizType::class, $quiz);

        $form->handleRequest($request);

        $quiz = $form->getData();

        if($form->isSubmitted() &&
            $form->isValid() &&
            true === $this->quizDataChecker->checkData($quiz)
        ){

            $this->quizService->create($quiz);

            $this->quizService->commit($quiz);

            return $this->redirectToRoute('quiz.show');
        }

        return $this->render('quizzes/quiz_create.html.twig', [
            'form' => $form->createView(),
            'action' => 'create',
            'errors' => $this->quizDataChecker->getMessages()
        ]);
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