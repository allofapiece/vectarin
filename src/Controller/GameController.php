<?php

namespace App\Controller;


use App\Entity\UserAnswer;
use App\Form\GameType;
use App\Service\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends Controller
{

    private $gameService;


    public function __construct(
        GameService $gameService
    )
    {
        $this->gameService = $gameService;
    }

    /**
     * @Route("/game/{gameId}")
     * @Route("/game/{gameId}/show/{questionNumber}",
     *     name="game.show",
     *     requirements={
     *          "questionNumber" = "\d+",
     *          "gameId" = "\d+"
     *      }
     *    )
     * @param int $gameId
     * @param int $questionNumber
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function showGame(int $gameId, int $questionNumber)
    {
        $game = $this->gameService->find($gameId);

        if (!$this->gameService->checkPermission($game, $this->getUser())) {
            throw $this->createNotFoundException();
        }

        $currentQuestion = $this->gameService->getCurrentQuestionNumber($game);

        if ($currentQuestion != $questionNumber) {
            return $this->redirectToRoute('game.show', [
                'gameId' => $gameId,
                'questionNumber' => $currentQuestion
            ]);
        }

        if ($this->gameService->gameIsOver($gameId) || $game->getGameIsOver()) {

            return $this->render('games/game_show.html.twig', [
                'gameInfo' => $this->gameService->find($gameId),
                'quizId'=>$game->getQuiz()->getId(),
            ]);
        }

        $this->gameService->setNextQuestion($game);

        return $this->render('games/game_show.html.twig', [
            'gameInfo' => $this->gameService->find($gameId),
            'nextQuestion' => $questionNumber + 1,
            'gameId' => $gameId,
        ]);
    }

    /**
     * @Route("/game/{gameId}/{questionNumber}",
     *      name="game.play",
     *     requirements={
     *          "questionNumber" = "\d+",
     *          "gameId" = "\d+"
     *      })
     * @param Request $request
     * @param $gameId
     * @param $questionNumber
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function playGame(Request $request, $gameId, $questionNumber)
    {
        $game = $this->gameService->find($gameId);

        if ($game == null || !$this->gameService->checkPermission($game, $this->getUser())) {
            throw $this->createNotFoundException();
        }

        if ($game->getGameIsOver()) {

            return $this->redirectToRoute('game.results', [
                'gameId' => $gameId,
                'quizId' => $game->getQuiz()->getId(),
            ]);
        }

        $currentQuestionNumber = $this->gameService->getCurrentQuestionNumber($game);

        if ($currentQuestionNumber != $questionNumber) {
            return $this->redirectToRoute('game.play', [
                'gameId' => $game->getId(),
                'questionNumber' => $currentQuestionNumber
            ]);
        }

        $form = $this->createForm(GameType::class, new UserAnswer());
        $form->handleRequest($request);

        $this->gameService->play($gameId);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $form->getData();

            $this->gameService->checkResult($result, $game->getCurrentQuestion());
            $this->gameService->stop();

            return $this->redirectToRoute('game.show', [
                'gameId' => $gameId,
                'questionNumber' => $questionNumber
            ]);
        }

        return $this->render('games/game_play.html.twig', [
            'form' => $form->createView(),
            'question' => $this->gameService->getCurrentQuestion($game),
            'answers' => $this->gameService->getAnswersForQuestion(),
            'gameId' => $gameId,
            'questionNumber' => $currentQuestionNumber,
        ]);
    }

    /**
     * @Route("/game/results/{quizId}", name="game.results",
     *     requirements={
     *          "gameId" = "\d+"
     *      })
     * @param int $quizId
     * @return Response
     */
    public function showResults(int $quizId)
    {
        $games = $this->gameService->findAllByQuizId($quizId);

        if (!$this->gameService->checkUserParticipation($games, $this->getUser()->getId())) {

            $this->addFlash(
                'notice',
                'Чтобы посмотреть результаты, вам нужно пройти викторину!'
            );
            return $this->redirectToRoute('quiz.show');
        }

        $thisUserResult = $this->gameService->findByQuizUserId($this->getUser()->getId(), $quizId);
        $thisUserPosition=$this->gameService->getUserPosition($games, $this->getUser()->getId());

        $users = $this->gameService->getTopUsers($games);

        return $this->render('games/game_result.html.twig', [
            'users' => $users,
            'thisUserResult'=>$thisUserResult->getCorrectQuestionsAmount(),
            'thisUserPosition'=>$thisUserPosition,
        ]);
    }

    /**
     * @Route("/game/create/{quizId}", name="game.create",
     *     requirements={
     *          "questionNumber" = "\d+",
     *          "gameId" = "\d+"
     *      })
     * @param int $quizId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createGame(int $quizId)
    {
        $game = $this->gameService->findByQuizUserId($this->getUser()->getId(), $quizId);

        if ($game) {
            if ($game->getGameIsOver()) {
                $this->addFlash(
                    'notice',
                    'Вы уже прошли эту викторину! Вы можете узнать результат в разделе "Мои викторины"'
                );
            } else {
                $this->addFlash(
                    'notice',
                    'Вы уже играете в эту викторину! Вы можете продолжить в разделе "Мои викторины"'
                );
            }

            return $this->redirectToRoute('quiz.show');
        }
        $gameId = $this
            ->gameService
            ->createGame($quizId, $this->getUser()->getId());

        return $this->redirectToRoute('game.play', ['gameId' => $gameId,
            'questionNumber' => 0]);
    }
}