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
}