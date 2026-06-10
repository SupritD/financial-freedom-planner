<?php

namespace Domain\SharedKernel\ValueObjects;

use Domain\SharedKernel\Exceptions\DomainException;
use InvalidArgumentException;

final readonly class Money
{
    private const SCALE = 4;

    private string $amount;
    private Currency $currency;

    public function __construct(
        string|float|int $amount,
        Currency $currency
    ) {
        if (!is_numeric($amount)) {
            throw new InvalidArgumentException("Amount must be numeric.");
        }
        
        // Normalize amount to scale
        $this->amount = bcadd((string)$amount, '0', self::SCALE);
        $this->currency = $currency;
    }

    public static function of(string|float|int $amount, string|Currency $currency = 'INR'): self
    {
        if (is_string($currency)) {
            $currency = new Currency($currency);
        }
        
        if (is_float($amount)) {
            // Convert float to string accurately without precision loss
            $amount = number_format($amount, self::SCALE, '.', '');
        }

        return new self((string) $amount, $currency);
    }

    public static function zero(string|Currency $currency = 'INR'): self
    {
        return self::of('0', $currency);
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function add(Money $other): self
    {
        $this->assertSameCurrency($other);
        return new self(bcadd($this->amount, $other->amount, self::SCALE), $this->currency);
    }

    public function subtract(Money $other): self
    {
        $this->assertSameCurrency($other);
        return new self(bcsub($this->amount, $other->amount, self::SCALE), $this->currency);
    }

    public function multiply(string|int|float $multiplier): self
    {
        $multiplierStr = is_float($multiplier) ? number_format($multiplier, self::SCALE, '.', '') : (string) $multiplier;
        return new self(bcmul($this->amount, $multiplierStr, self::SCALE), $this->currency);
    }

    public function divide(string|int|float $divisor): self
    {
        $divisorStr = is_float($divisor) ? number_format($divisor, self::SCALE, '.', '') : (string) $divisor;
        if (bccomp($divisorStr, '0', self::SCALE) === 0) {
            throw new DomainException("Cannot divide by zero.");
        }
        return new self(bcdiv($this->amount, $divisorStr, self::SCALE), $this->currency);
    }

    public function compareTo(Money $other): int
    {
        $this->assertSameCurrency($other);
        return bccomp($this->amount, $other->amount, self::SCALE);
    }

    public function isGreaterThan(Money $other): bool
    {
        return $this->compareTo($other) === 1;
    }

    public function isGreaterThanOrEqual(Money $other): bool
    {
        return $this->compareTo($other) >= 0;
    }

    public function isLessThan(Money $other): bool
    {
        return $this->compareTo($other) === -1;
    }

    public function isLessThanOrEqual(Money $other): bool
    {
        return $this->compareTo($other) <= 0;
    }

    public function isEqual(Money $other): bool
    {
        return $this->compareTo($other) === 0;
    }

    public function isZero(): bool
    {
        return bccomp($this->amount, '0', self::SCALE) === 0;
    }

    public function isPositive(): bool
    {
        return bccomp($this->amount, '0', self::SCALE) === 1;
    }

    public function isNegative(): bool
    {
        return bccomp($this->amount, '0', self::SCALE) === -1;
    }

    public function format(): string
    {
        // Round for display to 2 decimal places
        $rounded = bcadd($this->amount, '0.005', 2);
        return number_format((float) $rounded, 2, '.', ',');
    }

    private function assertSameCurrency(Money $other): void
    {
        if (!$this->currency->equals($other->currency)) {
            throw new DomainException("Currency mismatch: {$this->currency->getCode()} != {$other->currency->getCode()}");
        }
    }
}
