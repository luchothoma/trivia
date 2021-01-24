<?php
namespace Trivia;

use Trivia\Game\Game;
use Trivia\Game\Printer\IPrinter;
use Trivia\IRandomGameRunnerDataObtainer;

class GameRunner {
    private $gameDataObtainer = null;
    private $game = null;
    private $printer = null;

    public function __construct(
        IPrinter $gamePrinter,
        IPrinter $gameRunnerPrinter,
        IRandomGameRunnerDataObtainer $gameDataObtainer,
        string ...$playersName
    ) {
        $this->printer = $gameRunnerPrinter;
        $this->gameDataObtainer = $gameDataObtainer;
        $game = new Game($gamePrinter);
        array_walk($playersName, function($playerName) use($game){ $game->add($playerName); });
        $this->game = $game;
    }

    public function run() :void {               
        do {
            $roll = $this->gameDataObtainer->rollValue();
            $this->printer->echoln("Roll: ".$roll);
            $this->game->roll($roll);

            $isAnswerWrong = $this->gameDataObtainer->isAnswerWrong();
            $this->printer->echoln("AnswerIsWrong: ".($isAnswerWrong? 'true': 'false'));
            if ($isAnswerWrong) {
                $notAWinner = $this->game->wrongAnswer();
            } else {
                $notAWinner = $this->game->wasCorrectlyAnswered();
            }
        } while ($notAWinner);
    }
}