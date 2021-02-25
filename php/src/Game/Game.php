<?php
namespace Trivia\Game;

use Trivia\Game\Player\Player;
use Trivia\Game\Printer\IPrinter;
use Trivia\Game\Question\QuestionType;
use Trivia\Game\Question\QuestionMaker;

class Game {
    private const SCORE_TO_WIN_GAME = 6;

    private $printer = null;

    private $players = [];
    private $places = [0];

    private $popQuestions = [];
    private $scienceQuestions = [];
    private $sportsQuestions = [];
    private $rockQuestions = [];

    private $currentPlayerIndex = 0;
    private $isGettingOutOfPenaltyBox;

    public function  __construct(
        IPrinter $printer
    ){
        $this->printer = $printer;
        $this->initializeQuestions();
    }

    private function initializeQuestions() :void {
        for ($i = 0; $i < 50; $i++) {
            $questionNumber = $i;
            $questionContent = " Question {$questionNumber}";
			array_push($this->popQuestions, QuestionMaker::create(QuestionType::Pop(), $questionContent));
			array_push($this->scienceQuestions, QuestionMaker::create(QuestionType::Science(), $questionContent));
			array_push($this->sportsQuestions, QuestionMaker::create(QuestionType::Sports(), $questionContent));
			array_push($this->rockQuestions, QuestionMaker::create(QuestionType::Rock(), $questionContent));
    	}
    }

    private function currentPlayer() :Player {
        return $this->players[$this->currentPlayerIndex];
    }

	public function isPlayable() :bool {
		return ($this->howManyPlayers() >= 2);
	}

	public function add(string $playerName) :void {
        $player = Player::FromScalarValue($this->howManyPlayers(), $playerName);
        array_push($this->players, $player);
        $this->places[$this->howManyPlayers()] = 0;
        $this->printer->echoln($playerName . " was added");
        $this->printer->echoln("They are player number " . count($this->players));
	}

	private function howManyPlayers() :int {
		return count($this->players);
	}

	public function roll(int $rolledValue) :void {
        $player = $this->currentPlayer();
		$this->printer->echoln($player . " is the current player");
		$this->printer->echoln("They have rolled a " . $rolledValue);

		if (!$player->isAtPenaltyBox()) {
            $this->places[$this->currentPlayerIndex] = $this->places[$this->currentPlayerIndex] + $rolledValue;
			if ($this->places[$this->currentPlayerIndex] > 11) {
                $this->places[$this->currentPlayerIndex] = $this->places[$this->currentPlayerIndex] - 12;
            }
			$this->printer->echoln($player
					. "'s new location is "
					.$this->places[$this->currentPlayerIndex]);
			$this->printer->echoln("The category is " . $this->currentCategory());
            $this->askQuestion();
            return;
        }
        if ($rolledValue % 2 != 0) {
            $this->isGettingOutOfPenaltyBox = true;

            $this->printer->echoln($player . " is getting out of the penalty box");
            $this->places[$this->currentPlayerIndex] = $this->places[$this->currentPlayerIndex] + $rolledValue;
            if ($this->places[$this->currentPlayerIndex] > 11) $this->places[$this->currentPlayerIndex] = $this->places[$this->currentPlayerIndex] - 12;

            $this->printer->echoln($player
                    . "'s new location is "
                    .$this->places[$this->currentPlayerIndex]);
            $this->printer->echoln("The category is " . $this->currentCategory());
            $this->askQuestion();
            return;
        }
        $this->printer->echoln($player . " is not getting out of the penalty box");
        $this->isGettingOutOfPenaltyBox = false;
	}

	private function askQuestion() :void {
		$questionToAsk = array_shift($this->{strtolower($this->currentCategory()).'Questions'});
		$this->printer->echoln($questionToAsk);
	}

	private function currentCategory() :string {
		switch ($this->places[$this->currentPlayerIndex]) {
            case 0: case 4: case 8:
                return "Pop";
            case 1: case 5: case 9:
                return "Science";
            case 2: case 6: case 10:
                return "Sports";
            default:
                return "Rock";
        }
	}

    private function moveToNextPlayerTurn() :void {
        $this->currentPlayerIndex++;
        if ($this->currentPlayerIndex == count($this->players)) {
            $this->currentPlayerIndex = 0;
        }
    }

    public function wasCorrectlyAnswered() :bool {
        $player = $this->currentPlayer();
		if (!$player->isAtPenaltyBox() || $this->isGettingOutOfPenaltyBox) {
            $this->printer->echoln("Answer was correct!!!!");
            $player->questionAnsweredWell();
            $this->printer->echoln($player
                    . " now has "
                    .$player->points()
                    . " Gold Coins.");    
            $isTheWinner = $this->didPlayerWin($player);
            $this->moveToNextPlayerTurn();
            return !$isTheWinner;
        }
        $this->moveToNextPlayerTurn();
        return true;
    }
        
	public function wrongAnswer() :bool {
        $player = $this->currentPlayer();
		$this->printer->echoln("Question was incorrectly answered");
		$this->printer->echoln($player . " was sent to the penalty box");
	    $player->sendToPenaltyBox();
        $this->moveToNextPlayerTurn();
		return true;
	}

	private function didPlayerWin(Player $player) :bool {
		return $player->reachScore(self::SCORE_TO_WIN_GAME);
	}
}
