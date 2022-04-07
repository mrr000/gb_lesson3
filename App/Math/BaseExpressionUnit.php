<?php

namespace App\Math;

class BaseExpressionUnit implements IExpressionUnit
{
    public function __construct(protected float|string $value)
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}