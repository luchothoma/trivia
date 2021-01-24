<?php
namespace Trivia;

use Trivia\Game\Game;
use Trivia\Game\Printer\DefaultPrinter;

require_once __DIR__."/../vendor/autoload.php";

$notAWinner;
$aDefaultPrinter = new DefaultPrinter();
$aGame = new Game($aDefaultPrinter);

$aGame->add("Chet");
$aGame->add("Pat");
$aGame->add("Sue");

do {
    $aGame->roll(rand(0,5) + 1);

    if (rand(0,9) == 7) {
    $notAWinner = $aGame->wrongAnswer();
    } else {
    $notAWinner = $aGame->wasCorrectlyAnswered();
    }
} while ($notAWinner);
  
