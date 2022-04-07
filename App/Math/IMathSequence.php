<?php

namespace App\Math;

interface IMathSequence
{
    /**
     * @throws \Exception
     */
    public function __construct(string $string);

    public function toString(): string;

    public function getStack(): ExpressionUnitStack;
}