<?php
namespace Trivia\Game\Question;

use Trivia\Game\Question\Question;
use Trivia\Game\Question\QuestionType;

class QuestionMaker {
    public static function create(QuestionType $type, string $content) {
        return new Question($type->value().$content);
    }
}
