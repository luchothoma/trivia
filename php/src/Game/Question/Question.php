<?php
namespace Trivia\Game\Question;

use InvalidArgumentException;

class Question {
    private $value;

    public function __construct(string $value) {
        if($value==='') {
            throw new InvalidArgumentException("The question cannot be an empty string");
        }
        $this->value = $value;
    }
    public function value() :string {
        return $this->value;
    }
    public function __toString() :string {
        return $this->value();
    }
}
