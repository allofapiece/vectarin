<?php

namespace App\Controller;


use App\Entity\Question;
use App\Form\Question\QuestionCreateType;
use App\Service\QuestionService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends Controller
{
    /**
     * @Route("/admin/question/create", name="question.create")
     * @return Response
     */
    public function createQuestion(Request $request, QuestionService $questionService)
    {
        $question = new Question();

        $form = $this->createForm(QuestionCreateType::class, $question);

        $form->handleRequest($request);

        $question = $form->getData();



        if($form->isSubmitted() && $form->isValid()){

            $questionService->updateDBEntity($question);

            return $this->redirectToRoute('home');
        }

        return $this->render('questions/create_question.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/question/update/{id}",name="question.update")
     * @return Response
     */
    public function updateQuestion($id)
    {
        return new Response();
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
            2
        );

        return $this->render('questions/questions_show.html.twig', [
            'pagination' => $paginator,
        ]);
    }

    /**
     * @Route("/admin/question/show/{id}",name="question.show.id")
     * @return Response
     */
    public function showQuestionById()
    {
        return new Response();
    }
}