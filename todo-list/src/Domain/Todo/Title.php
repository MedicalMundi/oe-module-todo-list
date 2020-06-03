<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Domain\Todo;

final class Title
{
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function fromString(string $title): Title
    {
        return new self($title);
    }

    public function equals(Title $other): bool
    {
        return $this->value === $other->value;
    }
}
