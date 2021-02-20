<?php
namespace Trivia\Game\Player;

use InvalidArgumentException;

class PlayerName {
    private $value;

    public function __construct(?string $value) {
        if(empty($value)) {
            throw new InvalidArgumentException("The player name cannot be an empty string");
        }
        $this->value = $value;
    }
    public function value() :string {
        return $this->value;
    }
    public function equals(self $other) :bool {
        return $this->value === $other->value;
    }
    public function __toString() :string {
        return $this->value;
    }
}
