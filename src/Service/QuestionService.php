<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 04.04.2018
 * Time: 10:18
 */

namespace App\Service;


use App\Entity\Answer;
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

    private $answerService;
    /**
     * QuestionService constructor.
     * @param EntityManagerInterface $entityManager
     * @param QuestionOptimization $questionOptimization
     * @param PaginatorInterface $paginator
     */
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

    /**
     * @param Question $question
     * @return void
     */
    public function create(Question $question): void
    {
        $this->questionOptimization->optimizeQuestionText($question);
        $this->questionOptimization->addQuestionCharacterIfNotExist($question);
    }

    /**
     * @param Question $question
     * @param ArrayCollection $originalAnswers
     * @return void
     */
    public function update(Question $question, ArrayCollection $originalAnswers): void
    {
        $this->questionOptimization->optimizeQuestionText($question);
        $this->questionOptimization->addQuestionCharacterIfNotExist($question);

        foreach ($originalAnswers as $answer) {
            if (false === $question->getAnswers()->contains($answer)) {

                $answer->setQuestion(null);

                $this->entityManager->persist($answer);
                $this->entityManager->remove($answer);

            }
        }
    }

    /**
     * @param Question $question
     * @return void
     */
    public function commit(Question $question): void
    {
        $this->entityManager->persist($question);
        $this->entityManager->flush();
    }

    
    /**
     * @param int $id
     * @return Question
     */
    public function find(int $id): Question{
        $question = $this
            ->entityManager
            ->getRepository(Question::class)
            ->find($id);

        //TODO should create custom exception

        return $question;
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $questions = $this
            ->entityManager
            ->getRepository(Question::class)
            ->findAll();
        return $questions;
    }

    public function delete(Question $question)
    {
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