<?php

namespace Domain\SharedKernel\ValueObjects;

use InvalidArgumentException;

final readonly class Currency
{
    private string $code;

    public function __construct(string $code)
    {
        $code = strtoupper(trim($code));
        
        if (strlen($code) !== 3) {
            throw new InvalidArgumentException("Currency code must be 3 characters.");
        }

        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function equals(Currency $other): bool
    {
        return $this->code === $other->code;
    }

    public function __toString(): string
    {
        return $this->code;
    }
}
