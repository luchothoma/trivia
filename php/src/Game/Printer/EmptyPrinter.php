<?php
namespace Trivia\Game\Printer;

use Trivia\Game\Printer\IPrinter;

class EmptyPrinter implements IPrinter {
    public function echoln(string $string) :void {
    }
}