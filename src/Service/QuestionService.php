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
use Doctrine\ORM\Query;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class QuestionService
{
    private $entityManager;

    private $questionOptimization;

    private $paginator;

    public function __construct(
        EntityManagerInterface $entityManager,
        QuestionOptimization $questionOptimization,
        PaginatorInterface $paginator
    )
    {
        $this->entityManager = $entityManager;
        $this->questionOptimization = $questionOptimization;
        $this->paginator = $paginator;
    }

    public function create(Question $question)
    {
        $this->questionOptimization->optimizeQuestionText($question);
        $this->questionOptimization->addQuestionCharacterIfNotExist($question);

        $this->entityManager->persist($question);
        $this->entityManager->flush();
    }

    public function update(Question $question, ArrayCollection $originalAnswers)
    {
        $this->questionOptimization->optimizeQuestionText($question);
        $this->questionOptimization->addQuestionCharacterIfNotExist($question);

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

    public function find(int $id): Question{
        $question = $this
            ->entityManager
            ->getRepository(Question::class)
            ->find($id);

        //TODO should create custom exception

        return $question;
    }

    public function delete(Question $question){


        //TODO should create custom exception
        /*if (!$question) {
            throw $this->createNotFoundException('Данный вопрос не найден!');
        }*/

        $this->entityManager->remove($question);

        $this->entityManager->flush();
    }

    /**
     * @param int $usersOnPage
     * @param Request $request
     * @return PaginatorInterface
     */
    public function createPaginator(int $usersOnPage, Request $request)
    {
        $this->paginator = $this->paginator->paginate(
            $this->findAllQuery(),
            $request->query->getInt('page', 1),
            $usersOnPage
        );

        return $this->paginator;
    }

    /**
     * @return Query
     */
    public function findAllQuery(): Query
    {
        $dql = "SELECT u FROM App\Entity\Question u";

        $query = $this->entityManager->createQuery($dql);

        return $query;
    }

    /**
     * @return PaginatorInterface
     */
    public function getPaginator()
    {
        return $this->paginator;
    }

    /**
     * @param PaginatorInterface $paginator
     * @return void
     */
    public function setPaginator(PaginatorInterface $paginator): void
    {
        $this->paginator = $paginator;
    }



}