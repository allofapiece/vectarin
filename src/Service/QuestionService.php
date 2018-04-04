<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 04.04.2018
 * Time: 10:18
 */

namespace App\Service;


use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;

class QuestionService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateDBEntity(Question $question)
    {
        $this->entityManager->persist($question);
        $this->entityManager->flush();
    }

}