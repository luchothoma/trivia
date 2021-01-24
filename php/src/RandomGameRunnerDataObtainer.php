<?php
namespace Trivia;

use Trivia\IRandomGameRunnerDataObtainer;

class RandomGameRunnerDataObtainer implements IRandomGameRunnerDataObtainer {
    public function rollValue() :int {
        return rand(0,5) + 1;
    }
    public function isAnswerWrong() :bool {
        return rand(0,9) == 7;
    }
}