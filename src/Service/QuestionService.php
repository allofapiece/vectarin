<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 04.04.2018
 * Time: 10:18
 */

namespace App\Service;


use App\Entity\Question;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class QuestionService
{
    private $entityManager;
    private $questionOptimization;

    public function __construct(
        EntityManagerInterface $entityManager,
        QuestionOptimization $questionOptimization
    )
    {
        $this->entityManager = $entityManager;
        $this->questionOptimization = $questionOptimization;
    }

    public function create(Question $question)
    {
        $this->questionOptimization->optimizeQuestionText($question);

        $this->entityManager->persist($question);
        $this->entityManager->flush();
    }

    public function update(Question $question, ArrayCollection $originalAnswers)
    {
        $this->questionOptimization->optimizeQuestionText($question);

        // remove the relationship between the tag and the Task
        foreach ($originalAnswers as $answer) {
            if (false === $question->getAnswers()->contains($answer)) {
                // remove the Task from the Tag
                $answer->getQuestions()->removeElement($question);

                // if it was a many-to-one relationship, remove the relationship like this
                // $answer->setTask(null);

                $this->entityManager->persist($answer);

                // if you wanted to delete the Tag entirely, you can also do that
                // $entityManager->remove($answer);
            }
        }

        $this->entityManager->persist($question);
        $this->entityManager->flush();
    }

}