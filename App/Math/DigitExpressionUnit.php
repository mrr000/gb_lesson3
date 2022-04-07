<?php

namespace App\Math;

class DigitExpressionUnit extends BaseExpressionUnit
{
    private bool $reverseSign = false;

    /**
     * @return bool
     */
    public function isReverseSign(): bool
    {
        return $this->reverseSign;
    }

    /**
     * @param bool $reverseSign
     */
    public function setReverseSign(bool $reverseSign): void
    {
        $this->reverseSign = $reverseSign;
    }

    public function getValue(): string
    {
        if ($this->reverseSign) {
            return -parent::getValue();
        }
        return parent::getValue();
    }
}