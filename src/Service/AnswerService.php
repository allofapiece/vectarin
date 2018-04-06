<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 06.04.2018
 * Time: 22:49
 */

namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;

class AnswerService
{

    private $entityManager;

    /**
     * AnswerService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $answer
     * @return void
     */
    public function deleteAnswer($answer): void
    {
        $this->entityManager->remove($answer);

        $this->entityManager->flush();
    }
}