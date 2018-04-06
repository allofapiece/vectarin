<?php

namespace App\Controller;


use App\Entity\Question;
use App\Form\Question\QuestionType;
use App\Service\DataChecker\QuestionDataChecker;
use App\Service\QuestionOptimization;
use App\Service\QuestionService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends Controller
{
    private $questionDataChecker;
    private $questionOptimization;
    private $questionService;

    public function __construct(
        QuestionService $questionService,
        QuestionDataChecker $questionDataChecker
    )
    {
        $this->questionService = $questionService;
        $this->questionDataChecker = $questionDataChecker;
    }

    /**
     * @Route("/admin/question/create", name="question.create")
     * @return Response
     */
    public function createQuestion(Request $request)
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

            return $this->redirectToRoute('question.show');
        }

        return $this->render('questions/question_detail.html.twig', [
            'form' => $form->createView(),
            'errors' => $this->questionDataChecker->getMessages()
        ]);
    }

    /**
     * @Route("/admin/question/update/{id}",name="question.update")
     * @return Response
     */
    public function updateQuestion($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $question = $entityManager->getRepository(Question::class)->find($id);

        if (!$question) {
            throw $this->createNotFoundException('No task found for id '.$id);
        }

        $originalAnswers = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($question->getAnswers() as $answer) {
            $originalAnswers->add($answer);
        }

        $editForm = $this->createForm(QuestionType::class, $question);

        $editForm->handleRequest($request);

        if($editForm->isSubmitted() &&
            $editForm->isValid() &&
            false === $this->questionDataChecker->checkData($question)
        ){

            $this->questionService->update($question, $originalAnswers);

            // redirect back to question show page
            return $this->redirectToRoute('questions.show');
        }
        return $this->render('questions/question_detail.html.twig', [
            'form' => $editForm->createView(),
            'errors' => $this->questionDataChecker->getMessages()
        ]);
    }

    /**
     * @Route("/admin/question/delete/{id}",name="question.delete.id")
     * @return Response
     */
    public function deleteQuestion()
    {
        return new Response();
    }

    /**
     * @Route("/admin/questions/show",name="questions.show")
     * @param PaginatorInterface $paginator
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function showQuestions(PaginatorInterface $paginator, EntityManagerInterface $entityManager, Request $request)
    {
        $dql = "SELECT u FROM App\Entity\Question u";

        $query = $entityManager->createQuery($dql);

        $paginator = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('questions/questions_show.html.twig', [
            'pagination' => $paginator,
        ]);
    }

}