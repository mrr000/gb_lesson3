<?php

namespace App\Math;

interface IFunctionExpressionUnit
{
    /**
     * @return string
     */
    public function getPriority(): string;

    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @param mixed ...$args
     * @return string|float
     * @throws \ArithmeticError
     */
    public function execute(...$args): string|float;

    /**
     * @param mixed $value
     * @return bool
     */
    public static function isSupported(mixed $value): bool;
}