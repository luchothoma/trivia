<?php
namespace Test;

use Trivia\GameRunner;
use PHPUnit\Framework\TestCase;
use Trivia\Game\Printer\EmptyPrinter;
use Trivia\Game\Printer\StringCollectorPrinter;
use Trivia\IRandomGameRunnerDataObtainer;

final class GameRunnerTest extends TestCase
{
    public function possibleGameCasesDataProvider() :array {
        return [
            [
                ["Chet", "Pat", "Sue", "Lucho"],
                [1, 5, 1, 2, 5, 2, 2, 5, 3, 1, 4, 6, 6, 5, 2, 5, 5, 1, 5, 4, 4],
                [false, false, false, false, false, false, false, true, false, false, false, false, false, false, false, false, false, false, true, false, false]
            ],
        ];
    }
    /**
     * @dataProvider possibleGameCasesDataProvider
     */
    public function testGame(array $players, array $rollValues, array $isAnswerWrongValues): void {
        $gameDataObtainer = $this->gameDataObtainerUsingThisData($rollValues, $isAnswerWrongValues); 
        $anEmptyPrinterForGameRunner = new EmptyPrinter();
        $aStringCollectorPrinterForGameOuput = new StringCollectorPrinter();
        $aGameRunner = new GameRunner(
            $aStringCollectorPrinterForGameOuput,
            $anEmptyPrinterForGameRunner,
            $gameDataObtainer,
            ...$players
        );
        $aGameRunner->run();
        $this->assertEquals("", $aStringCollectorPrinterForGameOuput->collectedString());
    }

    private function gameDataObtainerUsingThisData(array $rollValues, array $isAnswerWrongValues) : IRandomGameRunnerDataObtainer {
        return new class ($rollValues, $isAnswerWrongValues) implements IRandomGameRunnerDataObtainer {
            private $rollValues;
            private $rollCount = 0;
            private $isAnswerWrongValues;
            private $isAnswerWrongCount = 0;
            public function __construct($rollValues, $isAnswerWrongValues) {
                $this->rollValues = $rollValues;
                $this->isAnswerWrongValues = $isAnswerWrongValues;
            }
            public function rollValue() :int {
                $rollValue = $this->rollValues[$this->rollCount];
                $this->rollCount++;
                return $rollValue;
            }
            public function isAnswerWrong() :bool {
                $isAnswerWringValue = $this->isAnswerWrongValues[$this->isAnswerWrongCount];
                $this->isAnswerWrongCount++;
                return $isAnswerWringValue;
            }
        };
    }
}