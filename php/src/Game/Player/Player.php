<?php
namespace Trivia\Game\Player;

use InvalidArgumentException;

class Player {
    private const INITIAL_POINTS = 0;
    private const POINTS_PER_QUESTION = 1;

    private $id;
    private $name;
    private $isAtPenaltyBox;
    private $points;

    public function __construct(PlayerId $id, PlayerName $name) {
        $this->id = $id;
        $this->name = $name;
        $this->isAtPenaltyBox = PenaltyBoxState::Out();
        $this->points = self::INITIAL_POINTS;
    }
    public static  function FromScalarValue(int $id, string $name) :self {
        return new self(new PlayerId($id), new PlayerName($name));
    }
    public function isAtPenaltyBox() :bool {
        return $this->isAtPenaltyBox->equals(PenaltyBoxState::Inside());
    }
    public function sendToPenaltyBox() :void {
        $this->isAtPenaltyBox = PenaltyBoxState::Inside();
    }
    public function sendOutOfPenaltyBox() :void {
        $this->isAtPenaltyBox = PenaltyBoxState::Out();
    }
    public function questionAnsweredWell() :void {
        $this->points += self::POINTS_PER_QUESTION;
    }
    public function points() :int {
        return $this->points;
    }
    public function reachScore(int $score) :bool {
        return $this->points === $score;
    }
    public function value() :string {
        return $this->value;
    }
    public function __toString() :string {
        return (string)$this->name;
    }
}
