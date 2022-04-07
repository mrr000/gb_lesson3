<?php

namespace App\Math;

class MultiplyExpressionUnit extends BaseExpressionUnit implements IFunctionExpressionUnit
{
    public function getPriority(): string
    {
        return 2;
    }

    public function execute(...$args): string|float
    {
        return (float)($args[0] ?? 0) * (float)($args[1] ?? 0);
    }

    public static function isSupported(mixed $value): bool
    {
        return $value === '*';
    }
}