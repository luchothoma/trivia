<?php
namespace Trivia\Game\Printer;

use Trivia\Game\Printer\IPrinter;

class StringCollectorPrinter implements IPrinter {
    private $collectedString = '';
    public function echoln(string $string) :void {
        $this->collectedString .= $string . PHP_EOL;
    }
    public function collectedString() :string {
        return $this->collectedString;
    }
}