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

    public function findAll(): array
    {
        $quizzes = $this
            ->entityManager
            ->getRepository(Quiz::class)
            ->findAll();

        return $quizzes;
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
     * @param Quiz $quiz
     * @param ArrayCollection $originalAnswers
     * @return void
     */
    public function update(Quiz $data, ArrayCollection $originalQuestions): void
    {
        //$this->questionOptimization->optimizeQuestionText($quiz);
        //$this->questionOptimization->addQuestionCharacterIfNotExist($quiz);
        foreach($data->getQuestions() as $question){

            if($question->getText() == null || $question->getText() == ""){

                $data->getQuestions()->removeElement($question);

            }

        }
        foreach($data->getQuestions() as $question){
            $isExist = false;
            foreach ($originalQuestions as $originalQuestion)
                if($question->getText() == $originalQuestion->getText()){
                    $isExist = true;
                    break;
            }
            if(!$isExist){
                $data->addQuestion($this
                    ->entityManager
                    ->getRepository(Question::class)
                    ->findOneBy(['text' => $question->getText()])
                );
                $data->removeQuestion($question);
            }
        }

        /*foreach ($originalQuestions as $question) {
            if (false === $data->getQuestions()->contains($question)) {
                $question->getQuizzes()->removeElement($data);

                $this->entityManager->remove($question);
                $this->entityManager->persist($question);

            }
        }*/

        $this->entityManager->flush($data);
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

    public function create(Quiz $data)
    {
        $quiz = new Quiz(
            $data->getName(),
            $data->getDescription(),
            true
        );

        foreach ($data->getQuestions() as $question) {
            $quiz->addQuestion($this
            ->entityManager
            ->getRepository(Question::class)
            ->findOneBy(['text' => $question->getText()])
            );
        }

        $this->entityManager->persist($quiz);
        $this->entityManager->flush();
    }

    /**
     * @param Quiz $quiz
     * @return void
     */
    public function delete(Quiz $quiz): void
    {
        //TODO should create custom exception
        /*if (!$quiz) {
            throw $this->createNotFoundException('Данный вопрос не найден!');
        }*/

        $this->entityManager->remove($quiz);
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