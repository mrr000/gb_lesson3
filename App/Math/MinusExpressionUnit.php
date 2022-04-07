<?php

namespace App\Math;

class MinusExpressionUnit extends BaseExpressionUnit implements IFunctionExpressionUnit, IUnaryFunctionExpressionUnit
{
    public function getPriority(): string
    {
        return 1;
    }

    public function execute(...$args): string|float
    {
        return (float)($args[0] ?? 0) - (float)($args[1] ?? 0);
    }

    public static function isSupported(mixed $value): bool
    {
        return $value === '-';
    }
}