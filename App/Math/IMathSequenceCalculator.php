<?php

namespace App\Math;

interface IMathSequenceCalculator
{
    /**
     * @throws \Exception
     */
    public function stackCalculate(ExpressionUnitStack $stack): ?float;
}