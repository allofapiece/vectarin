<?php

namespace App\Controller;


use App\Entity\Answer;
use App\Entity\Question;
use App\Form\Question\QuestionType;
use App\Service\DataChecker\QuestionDataChecker;
use App\Service\QuestionDeleter;
use App\Service\QuestionService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends Controller
{

    const QUESTIONS_ON_PAGE = 15;

    private $questionDeleter;

    private $questionDataChecker;

    private $questionService;

    /**
     * QuestionController constructor.
     * @param QuestionDeleter $questionDeleter
     * @param QuestionService $questionService
     * @param QuestionDataChecker $questionDataChecker
     */
    public function __construct(
        QuestionDeleter $questionDeleter,
        QuestionService $questionService,
        QuestionDataChecker $questionDataChecker
    )
    {
        $this->questionDeleter = $questionDeleter;
        $this->questionService = $questionService;
        $this->questionDataChecker = $questionDataChecker;
    }

    /**
     * @Route("/admin/question/create", name="question.create")
     * @param Request $request
     * @return Response
     */
    public function createQuestion(Request $request): Response
    {
        $question = new Question();

        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);

        $question = $form->getData();

        if($form->isSubmitted() &&
            $form->isValid() &&
            false === $this->questionDataChecker->checkData($question)
        ){

            $this->questionService->create($question);

            $this->questionService->commit($question);

            return $this->redirectToRoute('questions.show');
        }

        return $this->render('questions/question_create.html.twig', [
            'form' => $form->createView(),
            'action' => 'create',
            'errors' => $this->questionDataChecker->getMessages()
        ]);
    }

    /**
     * @Route("/admin/question/update/{id}", name="question.update")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function updateQuestion(int $id, Request $request): Response
    {
        $question = $this->questionService->find($id);

        if (!$question) {
            throw $this->createNotFoundException('No task found for id ' . $id);
        }

        $originalAnswers = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($question->getAnswers() as $answer) {
            $originalAnswers->add($answer);
        }

        $editForm = $this->createForm(QuestionType::class, $question);

        $editForm->handleRequest($request);

        $this->questionService->deleteEmptyAnswers($question);

        if($editForm->isSubmitted() &&
            $editForm->isValid() &&
            false === $this->questionDataChecker->checkData($question)
        ){

            $this->questionService->update($question, $originalAnswers);

            $this->questionService->commit($question);
            // redirect back to question show page
            return $this->redirectToRoute('questions.show');
        }

        return $this->render('questions/question_update.html.twig', [
            'form' => $editForm->createView(),
            'action' => 'update',
            'errors' => $this->questionDataChecker->getMessages()
        ]);
    }

    /**
     * @Route(
     *     "/admin/question/delete/{id}",
     *      name="question.delete",
     *      requirements={"id"="\d+"}
     * )
     *
     * @param int $id
     * @return Response
     */
    public function deleteQuestion(int $id): Response
    {
        if(!$this->questionDeleter->delete($id)){
            throw $this->createNotFoundException('Вопроса с индексом ' . $id . ' не существует ');
        }

        return $this->redirectToRoute('questions.show');
    }

    /**
     * @Route("/admin/questions/show",name="questions.show")
     * @param Request $request
     * @return Response
     */
    public function showQuestions(Request $request): Response
    {
        $this->questionService->createPaginator(self::QUESTIONS_ON_PAGE, $request);

        return $this->render('questions/questions_show.html.twig', [
            'pagination' => $this->questionService->getPaginator(),
        ]);
    }
}