<?php
namespace Test;

use Trivia\GameRunner;
use PHPUnit\Framework\TestCase;
use Trivia\Game\Printer\EmptyPrinter;
use Spatie\Snapshots\MatchesSnapshots;
use Trivia\IRandomGameRunnerDataObtainer;
use Trivia\Game\Printer\StringCollectorPrinter;

final class GameRunnerTest extends TestCase {
    use MatchesSnapshots;

    public function possibleGameCasesDataProvider() :array {
        return [
            [
                ["Chet", "Pat", "Sue", "Lucho"],
                [1, 5, 1, 2, 5, 2, 2, 5, 3, 1, 4, 6, 6, 5, 2, 5, 5, 1, 5, 4, 4],
                [false, false, false, false, false, false, false, true, false, false, false, false, false, false, false, false, false, false, true, false, false]
            ],
            [
                ["Lucho", "Gustavo"],
                [2, 1, 4, 5, 6, 5, 6, 1, 4, 3, 4, 2, 4, 6, 1, 6, 4, 5, 5, 1, 5, 5],
                [true, true, false, false, false, false, false, false, false, false, false, true, false, false, false, false, false, false, false, true, false, false]
            ],
            [
                ["Tylor", "Jhon", "Leandro"],
                [6, 4, 6, 1, 1, 1, 5, 4, 6, 1, 6, 2, 2, 3, 4, 1, 6],
                [false, false, false, false, false, false, false, false, false, false, false, false, true, false, false, false, false]
            ],
        ];
    }
    /**
     * @dataProvider possibleGameCasesDataProvider
     */
    public function testGame(array $players, array $rollValues, array $isAnswerWrongValues): void {
        $gameDataObtainer = $this->gameDataObtainerUsingThisData($rollValues, $isAnswerWrongValues); 
        $anEmptyPrinterForGameRunner = new EmptyPrinter();
        $aStringCollectorPrinterForGameOutput = new StringCollectorPrinter();
        $aGameRunner = new GameRunner(
            $aStringCollectorPrinterForGameOutput,
            $anEmptyPrinterForGameRunner,
            $gameDataObtainer,
            ...$players
        );
        $aGameRunner->run();
        $this->assertMatchesTextSnapshot($aStringCollectorPrinterForGameOutput->collectedString());
    }

    private function gameDataObtainerUsingThisData(array $rollValues, array $isAnswerWrongValues) : IRandomGameRunnerDataObtainer {
        $gameDataObtainer = $this->createMock(IRandomGameRunnerDataObtainer::class);
        $gameDataObtainer->expects($this->exactly(count($rollValues)))
            ->method('rollValue')
            ->willReturnOnConsecutiveCalls(...$rollValues);
        $gameDataObtainer->expects($this->exactly(count($isAnswerWrongValues)))
            ->method('isAnswerWrong')
            ->willReturnOnConsecutiveCalls(...$isAnswerWrongValues);
        return $gameDataObtainer;
    }
}