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

    private $quizService;

    private $quizDataChecker;

    private $updater;

    public function __construct(
        QuizCreateFormHandler $quizCreateFormHandler,
        QuizService $quizService,
        QuizUpdater $updater,
        QuizDataChecker $quizDataChecker
    )
    {
        $this->quizCreateFormHandler = $quizCreateFormHandler;
        $this->quizService = $quizService;
        $this->quizDataChecker = $quizDataChecker;
        $this->updater = $updater;
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
     * @return Response
     */
    public function updateQuiz(int $id, Request $request)
    {
        $quiz = $this->quizService->find($id);

        if (!$quiz) {
            throw $this->createNotFoundException('No task found for id ' . $id);
        }

        $originalQuestions = new ArrayCollection();

        foreach ($quiz->getQuestions() as $question) {
            $originalQuestions->add($question);
        }

        $editForm = $this->createForm(QuizType::class, $quiz);

        if(
            $request->isMethod('POST') &&
            true === $this->quizDataChecker->checkData($quiz)
        ){
            $editForm->submit($request->request->get($editForm->getName()));
            $data = $editForm->getData();
            $this->quizService->update($data, $originalQuestions);

            return $this->redirectToRoute('quiz.show');
        }

        return $this->render('quizzes/quiz_update.html.twig', [
            'form' => $editForm->createView(),
            'action' => 'update',
            'errors' => $this->quizDataChecker->getMessages()
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