<?php

namespace App\Utils;

interface IWriter
{
    public function write(string $string): void;

    public function writeLine(string $string): void;
}