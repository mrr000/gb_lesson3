<?php

namespace App\Math;

class ExpressionUnit
{
    /** @var IFunctionExpressionUnit[] */
    static $functionExpression = [
        PlusExpressionUnit::class,
        MinusExpressionUnit::class,
        DivideExpressionUnit::class,
        MultiplyExpressionUnit::class,
    ];

    /**
     * @param string|float $value
     * @return IExpressionUnit|null
     */
    public static function create(string|float $value): ?IExpressionUnit
    {
        if (is_numeric($value) || in_array($value, ['.', ','])) {
            return new DigitExpressionUnit($value);
        }
        if ($value === ')') {
            return new CloseBraceExpressionUnit($value);
        }
        if ($value === '(') {
            return new OpenBraceExpressionUnit($value);
        }

        /** @var IFunctionExpressionUnit $item */
        foreach (static::$functionExpression as $item) {
            if ($item::isSupported($value)) {
                return new $item($value);
            }
        }

        return null;
    }
}