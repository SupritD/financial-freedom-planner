<?php

namespace Domain\SharedKernel\Services;

use Domain\SharedKernel\ValueObjects\Money;
use Domain\SharedKernel\ValueObjects\Currency;

class MoneyCalculatorService
{
    public function add(Money $a, Money $b): Money
    {
        return $a->add($b);
    }

    public function subtract(Money $a, Money $b): Money
    {
        return $a->subtract($b);
    }

    public function multiply(Money $money, float|int|string $multiplier): Money
    {
        return $money->multiply($multiplier);
    }

    public function divide(Money $money, float|int|string $divisor): Money
    {
        return $money->divide($divisor);
    }

    public function calculatePercentage(Money $money, float|int|string $percentage): Money
    {
        $multiplier = bcdiv((string) $percentage, '100', 4);
        return $money->multiply($multiplier);
    }
}
