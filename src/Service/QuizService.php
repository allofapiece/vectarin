<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 07.04.2018
 * Time: 0:54
 */

namespace App\Service;


use App\Entity\Question;
use App\Entity\Quiz;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class QuizService
{
    private $entityManager;

    private $questionService;

    public function __construct(
        EntityManagerInterface $entityManager,
        QuestionService $questionService
    )
    {
        $this->entityManager = $entityManager;
        $this->questionService = $questionService;
    }

    /**
     * @param Quiz $quiz
     * @return void
     */
    public function deleteEmptyQuestions(Quiz $quiz): void
    {
        foreach($quiz->getQuestions() as $question){

            if($question->getText() == null || $question->getText() == ""){

                $quiz->getQuestions()->removeElement($question);

                $this->entityManager->remove($question);

                $this->questionService->deleteQuestion($question);
            }

        }

        $this->entityManager->flush();
    }

    /**
     * @param int $id
     * @return Quiz
     */
    public function find(int $id): Quiz
    {
        $quiz = $this
            ->entityManager
            ->getRepository(Quiz::class)
            ->find($id);

        //TODO should create custom exception

        return $quiz;
    }





    /**
     * @param Quiz $quiz
     * @return void
     */
    public function commit(Quiz $quiz): void
    {
        $this->entityManager->persist($quiz);
        $this->entityManager->flush();
    }
}