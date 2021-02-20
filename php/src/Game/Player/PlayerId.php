<?php
namespace Trivia\Game\Player;

use InvalidArgumentException;

class PlayerId {
    private $value;

    public function __construct(int $value) {
        if($value < 0) {
            throw new InvalidArgumentException("The player identifier cannot be a negative number");
        }
        $this->value = $value;
    }
    public function value() :int {
        return $this->value;
    }
    public function equals(self $other) :int {
        return $this->value === $other->value;
    }
    public function __toString() :string {
        return (string)$this->value;
    }
}
