<?php
namespace Trivia\Game\Question;

use InvalidArgumentException;

class QuestionType {
    private const POP = "Pop";
    private const SCIENCE = "Science";
    private const SPORTS = "Sports";
    private const ROCK = "Rock";

    private const ALLOWED_VALUES = [self::POP, self::SCIENCE, self::SPORTS, self::ROCK];

    private $value;

    public function __construct(string $value) {
        if(!in_array($value, self::ALLOWED_VALUES)) {
            throw new InvalidArgumentException("The question type given is not a valid one({$value}).");
        }
        $this->value = $value;
    }
    public static function Pop() :self {
        return new self(self::POP);
    }
    public static function Science() :self {
        return new self(self::SCIENCE);
    }
    public static function Sports() :self {
        return new self(self::SPORTS);
    }
    public static function Rock() :self {
        return new self(self::ROCK);
    }
    public function value() :string {
        return $this->value;
    }
}
