<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Application\Domain\Todo;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class TodoId implements \Stringable
{
    public static function generate(): TodoId
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $todoId): TodoId
    {
        return new self(Uuid::fromString(trim($todoId)));
    }

    private function __construct(
        private readonly UuidInterface $uuid
    ) {
    }

    public function toString(): string
    {
        return $this->uuid->toString();
    }

    public function __toString(): string
    {
        return $this->uuid->toString();
    }

    public function equals(TodoId $other): bool
    {
        return $this->uuid->equals($other->uuid);
    }
}
