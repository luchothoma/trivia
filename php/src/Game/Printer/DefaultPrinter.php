<?php
namespace Trivia\Game\Printer;

use Trivia\Game\Printer\IPrinter;

class DefaultPrinter implements IPrinter {
    public function echoln(string $string) :void {
      echo $string."\n";
    }
}