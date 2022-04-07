<?php

namespace App\Utils;

class CliWriter implements IWriter
{
    public function write(string $string): void
    {
        echo $string;
    }

    public function writeLine(string $string): void
    {
        $this->write($string. PHP_EOL);
    }
}