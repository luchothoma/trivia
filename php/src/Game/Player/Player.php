<?php
namespace Trivia\Game\Player;

use InvalidArgumentException;

class Player {
    private $id;
    private $name;
    private $isAtPenaltyBox;

    public function __construct(PlayerId $id, PlayerName $name) {
        $this->id = $id;
        $this->name = $name;
        $this->isAtPenaltyBox = PenaltyBoxState::Out();
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
    public function value() :string {
        return $this->value;
    }
    public function __toString() :string {
        return (string)$this->name;
    }
}
