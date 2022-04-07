<?php

namespace App\Math;

use App\Utils\Stack;

class MathSequenceCalculator implements IMathSequenceCalculator
{
    /**
     * @throws \Exception
     */
    public function stackCalculate(ExpressionUnitStack $stack): ?float
    {
        $calculateStack = new Stack();

        while (null !== ($popped = ($stack->pop()))) {
            if ($popped instanceof DigitExpressionUnit) {
                $calculateStack->push($popped->getValue());
            } elseif ($popped instanceof IFunctionExpressionUnit) {
                $second = $calculateStack->pop();
                $first = $calculateStack->pop();
                $result = $popped->execute($first, $second);

                $calculateStack->push($result);
            } else {
                throw new \Exception('Необрабатываемые входные данные: ' . $popped);
            }
        }

        return $calculateStack->pop();
    }
}