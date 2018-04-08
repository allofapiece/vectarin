<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 07.04.2018
 * Time: 0:54
 */

namespace App\Service;


use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;

class QuizService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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