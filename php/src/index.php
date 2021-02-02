<?php
namespace Trivia;

require_once __DIR__."/../vendor/autoload.php";

use Trivia\GameRunner;
use Trivia\Game\Printer\EmptyPrinter;
use Trivia\Game\Printer\DefaultPrinter;
use Trivia\RandomGameRunnerDataObtainer;

$playersName = ["Lucho", "Gustavo", "Andrew"];
$aGameRunner = new GameRunner( 
    new EmptyPrinter(),
    new DefaultPrinter(),
    new RandomGameRunnerDataObtainer(),
    ...$playersName
);
$aGameRunner->run();