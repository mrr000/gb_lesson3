<?php

namespace App\Utils;

interface IReader
{
    public function read(): array;
    public function readString(): string;
}