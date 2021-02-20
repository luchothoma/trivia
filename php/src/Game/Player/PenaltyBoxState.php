<?php
namespace Trivia\Game\Player;

use InvalidArgumentException;

class PenaltyBoxState {
    private const Out = 0;
    private const Inside = 1;

    private const StringRepresentationOfStates = [
        self::Out => 'Out',
        self::Inside => 'Inside',
    ];

    private const ALLOWED_VALUES = [
        self::Out,
        self::Inside,
    ];

    private $value;

    private function __construct(int $value) {
        if(!in_array($value, self::ALLOWED_VALUES, true)) {
            throw new InvalidArgumentException("The Penalty box state given it is not valid ({$value})");
        }
        $this->value = $value;
    }
    public static function Out() :self {
        return new self(self::Out);
    }
    public static function Inside() :self {
        return new self(self::Inside);
    }
    public function value() :int {
        return $this->value;
    }
    public function equals(self $other) :int {
        return $this->value === $other->value;
    }
    public function __toString() :string {
        return self::StringRepresentationOfStates[$this->value];
    }
}
