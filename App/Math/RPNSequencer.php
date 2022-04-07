<?php

namespace App\Math;

use App\Utils\Stack;
use \Exception;

/**
 * Reverse Polish notation Sequencer
 */
class RPNSequencer implements IMathSequence
{
    protected ExpressionUnitStack $resultStack;
    protected ExpressionUnitStack $stack;

    /**
     * @throws Exception
     */
    public function __construct(string $string)
    {
        $this->stack = new ExpressionUnitStack();
        $this->resultStack = new ExpressionUnitStack();
        $this->handleInputString($string);
    }

    /**
     * @throws Exception
     */
    protected function handleInputString(string $string)
    {
        $length = strlen($string);

        /** @var IExpressionUnit|null $previous */
        $previous = null;

        $setNextDigitalReverseSign = false;

        for ($i = 0; $i < $length; $i++) {
            $char = $string[$i];
            $nextChar = $string[$i + 1] ?? null;

            /** @var IExpressionUnit $current */
            $current = ExpressionUnit::create($char);

            /** @var IExpressionUnit|null $current */
            $next = $nextChar ? ExpressionUnit::create($nextChar) : null;

            switch (true) {
                case $current instanceof DigitExpressionUnit:
                    if ($setNextDigitalReverseSign) {
                        $current->setReverseSign(true);
                        $setNextDigitalReverseSign = false;
                    }

                    if ($previous instanceof DigitExpressionUnit) {
                        // prepend last char
                        $item = $this->resultStack->pop();
                        /** @var DigitExpressionUnit $newItem */
                        $newItem = ExpressionUnit::create($item->getValue() . $current->getValue());
                        $newItem->setReverseSign($current->isReverseSign());
                        $this->resultStack->push($newItem);
                    } else {
                        $this->resultStack->push($current);
                    }
                    break;

                case $current instanceof IFunctionExpressionUnit:
                    if ($next instanceof IFunctionExpressionUnit && $previous instanceof IFunctionExpressionUnit) {
                        throw new Exception('Указаны три знака подряд');
                    }

                    if ($previous instanceof IFunctionExpressionUnit && $current instanceof IUnaryFunctionExpressionUnit) {
                        if ($current instanceof MinusExpressionUnit) {
                            $setNextDigitalReverseSign = true;
                        }
                        break;
                    }

                    $isSameFunctionPriority = true;
                    while ($isSameFunctionPriority && (null !== ($popped = $this->stack->pop()))) {
                        if ($popped instanceof IFunctionExpressionUnit && $popped->getPriority() >= $current->getPriority()) {
                            $this->resultStack->push($popped);
                        } else {
                            $this->stack->push($popped);
                            $isSameFunctionPriority = false;
                        }
                    }

                    $this->stack->push($current);
                    break;
                case $current instanceof OpenBraceExpressionUnit:
                    $this->stack->push($current);
                    break;
                case $current instanceof CloseBraceExpressionUnit:
                    while (! (($popped = $this->stack->pop()) instanceof OpenBraceExpressionUnit)) {
                        if (null === $popped) {
                            throw new Exception('Скобки не согласованы');
                        }
                        $this->resultStack->push($popped);
                    }
                    break;
            }

            $previous = clone $current;
        }

        while (null !== ($popped = $this->stack->pop())) {
            if ($popped instanceof CloseBraceExpressionUnit || $popped instanceof OpenBraceExpressionUnit) {
                throw new Exception('Скобки не согласованы');
            }
            $this->resultStack->push($popped);
        }
    }

    public function getStack(): ExpressionUnitStack
    {
        return clone $this->resultStack;
    }

    public function toString(): string
    {
        return (string)$this;
    }

    public function __toString(): string
    {
        $clone = clone $this->resultStack;
        $result = '';
        while (null !== ($el = $clone->pop())) {
            $result = "{$el->getValue()} $result";
        }
        return $result;
    }
}