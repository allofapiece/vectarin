<?php

namespace App\Controller;


use App\Entity\Answer;
use App\Entity\Question;
use App\Form\Question\QuestionType;
use App\Service\DataChecker\QuestionDataChecker;
use App\Service\QuestionCreateFormHandler;
use App\Service\QuestionCreator;
use App\Service\QuestionDeleter;
use App\Service\QuestionService;
use App\Service\QuestionUpdateFormHandler;
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

    private $questionCreateFormHandler;

    private $questionUpdateFormHandler;

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
        QuestionCreateFormHandler $questionCreateFormHandler,
        QuestionUpdateFormHandler $questionUpdateFormHandler,
        QuestionDeleter $questionDeleter,
        QuestionService $questionService,
        QuestionDataChecker $questionDataChecker
    )
    {
        $this->questionCreateFormHandler = $questionCreateFormHandler;
        $this->questionUpdateFormHandler = $questionUpdateFormHandler;
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
        if($this->questionCreateFormHandler->handle($form, $request)){
            return $this->redirectToRoute('questions.show');
        }

        return $this->render('questions/question_create.html.twig', [
            'form' => $form->createView(),
            'action' => 'create',
            'errors' => $this->questionCreateFormHandler->getFormErrorMessages()
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
        $question = $this
            ->getDoctrine()
            ->getRepository(Question::class)
            ->find($id);

        if (!$question) {
            throw $this->createNotFoundException('Викторины с индексом ' . $id . ' не существует');
        }

        $form = $this->createForm(QuestionType::class, $question);
        if($this->questionUpdateFormHandler->handle($form, $request, ['entity' => $question])){
            return $this->redirectToRoute('questions.show');
        }

        return $this->render('questions/question_update.html.twig', [
            'form' => $form->createView(),
            'action' => 'update',
            'errors' => $this->questionUpdateFormHandler->getFormErrorMessages()
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