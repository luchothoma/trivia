<?php
namespace Trivia\Game;

use Trivia\Game\Printer\IPrinter;

class Game {
    private $printer = null;

    private $players;
    private $places;
    private $purses ;
    private $inPenaltyBox ;

    private $popQuestions;
    private $scienceQuestions;
    private $sportsQuestions;
    private $rockQuestions;

    private $currentPlayer = 0;
    private $isGettingOutOfPenaltyBox;

    public function  __construct(
        IPrinter $printer
    ){
        $this->printer = $printer;
        $this->initialize();
    }

    private function initialize() :void {
        $this->players = array();
        $this->places = array(0);
        $this->purses  = array(0);
        $this->inPenaltyBox  = array(0);

        $this->popQuestions = array();
        $this->scienceQuestions = array();
        $this->sportsQuestions = array();
        $this->rockQuestions = array();

        for ($i = 0; $i < 50; $i++) {
			array_push($this->popQuestions, "Pop Question " . $i);
			array_push($this->scienceQuestions, ("Science Question " . $i));
			array_push($this->sportsQuestions, ("Sports Question " . $i));
			array_push($this->rockQuestions, $this->createRockQuestion($i));
    	}
    }

	function createRockQuestion($index){
		return "Rock Question " . $index;
	}

	function isPlayable() {
		return ($this->howManyPlayers() >= 2);
	}

	function add($playerName) {
	   array_push($this->players, $playerName);
	   $this->places[$this->howManyPlayers()] = 0;
	   $this->purses[$this->howManyPlayers()] = 0;
	   $this->inPenaltyBox[$this->howManyPlayers()] = false;

	    $this->printer->echoln($playerName . " was added");
	    $this->printer->echoln("They are player number " . count($this->players));
		return true;
	}

	function howManyPlayers() {
		return count($this->players);
	}

	function  roll($roll) {
		$this->printer->echoln($this->players[$this->currentPlayer] . " is the current player");
		$this->printer->echoln("They have rolled a " . $roll);

		if ($this->inPenaltyBox[$this->currentPlayer]) {
			if ($roll % 2 != 0) {
				$this->isGettingOutOfPenaltyBox = true;

				$this->printer->echoln($this->players[$this->currentPlayer] . " is getting out of the penalty box");
			$this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $roll;
				if ($this->places[$this->currentPlayer] > 11) $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - 12;

				$this->printer->echoln($this->players[$this->currentPlayer]
						. "'s new location is "
						.$this->places[$this->currentPlayer]);
				$this->printer->echoln("The category is " . $this->currentCategory());
				$this->askQuestion();
			} else {
				$this->printer->echoln($this->players[$this->currentPlayer] . " is not getting out of the penalty box");
				$this->isGettingOutOfPenaltyBox = false;
				}

		} else {

		$this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $roll;
			if ($this->places[$this->currentPlayer] > 11) $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - 12;

			$this->printer->echoln($this->players[$this->currentPlayer]
					. "'s new location is "
					.$this->places[$this->currentPlayer]);
			$this->printer->echoln("The category is " . $this->currentCategory());
			$this->askQuestion();
		}

	}

	function  askQuestion() {
		if ($this->currentCategory() == "Pop")
			$this->printer->echoln(array_shift($this->popQuestions));
		if ($this->currentCategory() == "Science")
			$this->printer->echoln(array_shift($this->scienceQuestions));
		if ($this->currentCategory() == "Sports")
			$this->printer->echoln(array_shift($this->sportsQuestions));
		if ($this->currentCategory() == "Rock")
			$this->printer->echoln(array_shift($this->rockQuestions));
	}


	function currentCategory() {
		if ($this->places[$this->currentPlayer] == 0) return "Pop";
		if ($this->places[$this->currentPlayer] == 4) return "Pop";
		if ($this->places[$this->currentPlayer] == 8) return "Pop";
		if ($this->places[$this->currentPlayer] == 1) return "Science";
		if ($this->places[$this->currentPlayer] == 5) return "Science";
		if ($this->places[$this->currentPlayer] == 9) return "Science";
		if ($this->places[$this->currentPlayer] == 2) return "Sports";
		if ($this->places[$this->currentPlayer] == 6) return "Sports";
		if ($this->places[$this->currentPlayer] == 10) return "Sports";
		return "Rock";
	}

	function wasCorrectlyAnswered() {
		if ($this->inPenaltyBox[$this->currentPlayer]){
			if ($this->isGettingOutOfPenaltyBox) {
				$this->printer->echoln("Answer was correct!!!!");
			$this->purses[$this->currentPlayer]++;
				$this->printer->echoln($this->players[$this->currentPlayer]
						. " now has "
						.$this->purses[$this->currentPlayer]
						. " Gold Coins.");

				$winner = $this->didPlayerWin();
				$this->currentPlayer++;
				if ($this->currentPlayer == count($this->players)) $this->currentPlayer = 0;

				return $winner;
			} else {
				$this->currentPlayer++;
				if ($this->currentPlayer == count($this->players)) $this->currentPlayer = 0;
				return true;
			}



		} else {

			$this->printer->echoln("Answer was corrent!!!!");
		$this->purses[$this->currentPlayer]++;
			$this->printer->echoln($this->players[$this->currentPlayer]
					. " now has "
					.$this->purses[$this->currentPlayer]
					. " Gold Coins.");

			$winner = $this->didPlayerWin();
			$this->currentPlayer++;
			if ($this->currentPlayer == count($this->players)) $this->currentPlayer = 0;

			return $winner;
		}
	}

	function wrongAnswer(){
		$this->printer->echoln("Question was incorrectly answered");
		$this->printer->echoln($this->players[$this->currentPlayer] . " was sent to the penalty box");
	$this->inPenaltyBox[$this->currentPlayer] = true;

		$this->currentPlayer++;
		if ($this->currentPlayer == count($this->players)) $this->currentPlayer = 0;
		return true;
	}


	function didPlayerWin() {
		return !($this->purses[$this->currentPlayer] == 6);
	}
}
