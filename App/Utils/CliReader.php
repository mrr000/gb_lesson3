<?php

namespace App\Utils;

class CliReader implements IReader
{
    public function __construct(private $cliArgs = [])
    {
    }

    public function read(): array
    {
        return $this->cliArgs;
    }

    public function readString(): string
    {
        $args = $this->read();
        return $args[1] ?? '';
    }
}