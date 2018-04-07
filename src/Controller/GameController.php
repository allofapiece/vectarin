<?php
/**
 * Created by PhpStorm.
 * User: Listratsenka Stas
 * Date: 07.04.2018
 * Time: 11:48
 */

namespace App\Controller;

use App\Entity\Question;
use App\Form\GameType;
use App\Service\DataChecker\ResultDataChecker;
use App\Service\GameService;
use App\Service\QuestionService;
use App\Service\QuizService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends Controller
{

    private $gameService;

    private $resultDataChecker;

    public function __construct(
        GameService $gameService,
        ResultDataChecker $resultDataChecker
    )
    {
        $this->gameService = $gameService;
        $this->resultDataChecker = $resultDataChecker;
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
     *
     */
    public function showGame(int $gameId, int $questionNumber)
    {
        $game = $this->gameService->find($gameId);

        if(!$this->gameService->checkPermission($game,$this->getUser())){
            throw $this->createNotFoundException();
        }

        $currentQuestion = $this->gameService->getCurrentQuestion($game);

        if($currentQuestion != $questionNumber){
            return $this->redirectToRoute('game.show',[
                'gameId' => $gameId,
                'questionNumber' => $currentQuestion
            ]);
        }

        return $this->render('games/game_show.html.twig', [
            'isCorrect' => $this->gameService
        ]);

        //$this->gameService->play($gameId, $questionNumber);
    }

    /**
     * @Route("/game/{gameId}/play/{questionNumber}",
     *      name="game.play",
     *      requirements={
     *          "questionNumber" = "\d+",
     *          "gameId" = "\d+"
     *      })
     */
    public function playGame(int $gameId, int $questionNumber)
    {
        $game = $this->gameService->find($gameId);

        if($game == null || !$this->gameService->checkPermission($game,$this->getUser())){
            throw $this->createNotFoundException();
        }

        $currentQuestionNumber = $this->gameService->getCurrentQuestionNumber($game);

        if($currentQuestionNumber != $questionNumber){
            return $this->redirectToRoute('game.play',[
                'gameId' => $gameId,
                'questionNumber' => $currentQuestionNumber
            ]);
        }
        $form = $this->createForm(GameType::class);

        $this->gameService->play($gameId);

        $result = $form->get('result')->getData();

        if($form->isSubmitted && !$this->resultDataChecker->checkData($result)){

            $this->gameService->checkResult($result, $game->getCurrentQuestion());

            $this->gameService->stop();

            return $this->redirectToRoute('game.show', [
                'gameId' => $gameId,
                'questionNumber' => $questionNumber
            ]);
        }


        return $this->render('games/game_play.html.twig', [
            'form' => $form->createView()
        ]);

        //$this->gameService->play($gameId, $questionNumber);
    }

    /**
     * @Route("/game/create/{quizId}", name="game.create",
     *     requirements={
     *          "questionNumber" = "\d+",
     *          "gameId" = "\d+"
     *      })
     */
    public function createGame($quizId)
    {
        //$game = $this->gameService->find($gameId);

        //if($game == null)
        $gameId = $this
            ->gameService
            ->createGame($quizId, $this->getUser()->getId());

        return $this->redirectToRoute('game.play', [
            'gameId' => $gameId,
            'questionNumber' => 1
        ]);
    }
}