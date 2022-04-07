<?php

namespace App\Utils;

class Stack
{
    protected array $stack = [];

    /**
     * @throws \Exception
     */
    public function push($value): int
    {
        return array_push($this->stack, $value);
    }

    public function pop(): mixed
    {
        return array_pop($this->stack);
    }

    public function reverse(): self
    {
        $this->stack = array_reverse($this->stack);
        return $this;
    }
}