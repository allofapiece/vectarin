<?php

namespace App\Controller;


use App\Entity\Question;
use App\Form\Question\QuestionCreateType;
use App\Service\QuestionService;
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
     * @Route("/admin/question/update/{id}",name="question.create.id")
     * @return Response
     */
    public function updateQuestion()
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
     * @Route("/admin/question/show",name="question.show")
     * @return Response
     */
    public function showQuestions()
    {
        return $this->render('questions/questions.html.twig');
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