<?php

namespace Domain\SharedKernel\ValueObjects;

use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;

final readonly class TenantId
{
    private string $id;

    public function __construct(string $id)
    {
        if (!Uuid::isValid($id)) {
            throw new InvalidArgumentException("Invalid UUID for TenantId.");
        }

        $this->id = $id;
    }

    public static function generate(): self
    {
        return new self(Uuid::v4()->toRfc4122());
    }

    public function toString(): string
    {
        return $this->id;
    }

    public function equals(TenantId $other): bool
    {
        return $this->id === $other->toString();
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
