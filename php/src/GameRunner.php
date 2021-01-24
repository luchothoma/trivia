<?php
namespace Trivia;

use Trivia\Game\Game;
use Trivia\Game\Printer\DefaultPrinter;
use Trivia\Game\Printer\EmptyPrinter;

require_once __DIR__."/../vendor/autoload.php";

$notAWinner;
$aDefaultPrinter = new DefaultPrinter();
$anEmptyPrinter = new EmptyPrinter();
$aGame = new Game($anEmptyPrinter);

$playersName = ["Chet", "Pat", "Sue"];
array_walk($playersName, function($playerName) use($aGame){ $aGame->add($playerName); });

do {
    $roll = rand(0,5) + 1;
    $aDefaultPrinter->echoln("Roll: ".$roll);
    $aGame->roll(rand(0,5) + 1);

    $isAnswerWrong = rand(0,9) == 7;
    $aDefaultPrinter->echoln("AnswerIsWrong: ".($isAnswerWrong? 'true': 'false'));
    if ($isAnswerWrong) {
        $notAWinner = $aGame->wrongAnswer();
    } else {
        $notAWinner = $aGame->wasCorrectlyAnswered();
    }
} while ($notAWinner);