<?php

namespace App\Math;

interface IExpressionUnit
{
    public function __construct(string|float $value);

    /**
     * @return string
     */
    public function getValue(): string;
}