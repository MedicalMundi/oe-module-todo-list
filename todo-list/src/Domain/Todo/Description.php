<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Domain\Todo;

final class Description
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

    public static function fromString(string $description): Description
    {
        return new self($description);
    }
}
