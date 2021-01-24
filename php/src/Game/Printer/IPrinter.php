<?php
namespace Trivia\Game\Printer;

interface IPrinter {
    public function echoln(string $string) :void;
}