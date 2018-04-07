<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 07.04.2018
 * Time: 12:10
 */

namespace App\Service;


use App\Entity\Game;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class GameService
{
    private $entityManager;

    private $userInterface;

    private $timerService;

    private $game;

    private $isCorrect;

    public function __construct(
        EntityManagerInterface $entityManager,
        //UserInterface $userInterface,
        TimerService $timerService
    )
    {
        $this->entityManager = $entityManager;
        //$this->userInterface = $userInterface;
        $this->timerService = $timerService;

        $this->game = new Game();
    }

    public function checkPermission(Game $game, UserInterface $user): bool
    {
        $this->game = $game;



        $targetUser = $this
            ->entityManager
            ->getRepository(User::class)
            ->find($game->getUser());

        if($user->getUsername() != $targetUser->getUsername()){
            return false;
        }

        return true;
    }

    public function createGame(int $quizId, int $userId): int
    {
        $quiz = $this
            ->entityManager
            ->getRepository(Quiz::class)
            ->find($quizId);

        $user = $this
            ->entityManager
            ->getRepository(User::class)
            ->find($userId);

        $this->game
            ->setCurrentQuestion($quiz->getQuestions()[0])
            ->setQuiz($quiz)
            ->setUser($user)
            ->setStatus('in_proccess')
            ->setCorrectQuestionsAmount(0)
            ->setTime(0);

        $this->entityManager->persist($this->game);
        $this->entityManager->flush();

        return $this->game->getId();
    }

    public function play(int $gameId): ?Game
    {
        $game = $this
            ->entityManager
            ->getRepository(Game::class)
            ->find($gameId);

        $this->timerService->startTimer();

        $this->game = $game;

        return $this->game;
    }

    public function stop()
    {
        $this->timerService->stopTimer();

        $this->game->setTime($this->timerService->getTimerDuration());

        $this->entityManager->persist($this->game);
        $this->entityManager->flush();
    }

    public function checkResult(array $result, Question $question): bool
    {
        $i = 0;

        foreach ($question->getAnswers() as $answer){
            if($answer->getIsCorrect() != $result[$i]){
                $this->isCorrect = false;

                return false;
            }

            $i++;
        }

        $this->isCorrect = true;

        $this
            ->game
            ->setCorrectQuestionsAmount($this->game->getCorrectQuestionsAmount() + 1);

        $this->entityManager->persist($this->game);
        $this->entityManager->flush();

        return true;
    }

    public function getCurrentQuestionNumber(Game $game): ?int
    {
        $quiz = $game->getQuiz();

        $questions = $quiz->getQuestions();

        for ($i = 1; $i <= count($questions); $i++){
            if($questions[$i]->getId() == $game->getCurrentQuestion()->getId()){
                return $i;
            }
        }

        return null;
    }

    public function getCurrentQuestion(Game $game)
    {
        return $game->getCurrentQuestion();
    }

    public function find(int $gameId): ?Game
    {
        $game = $this
            ->entityManager
            ->getRepository(Game::class)
            ->find($gameId);

        $this->game = $game;

        return $game;
    }

    /**
     * @return mixed
     */
    public function getIsCorrect()
    {
        return $this->isCorrect;
    }

    /**
     * @param mixed $isCorrect
     */
    public function setIsCorrect($isCorrect)
    {
        $this->isCorrect = $isCorrect;
    }


}