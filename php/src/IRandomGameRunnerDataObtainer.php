<?php
namespace Trivia;

interface IRandomGameRunnerDataObtainer {
    public function rollValue() :int;
    public function isAnswerWrong() :bool;
}