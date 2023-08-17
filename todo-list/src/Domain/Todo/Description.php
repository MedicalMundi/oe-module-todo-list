<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Domain\Todo;

final class Description
{
    public const MIN_LENGHT = 5;

    public const MAX_LENGHT = 1500;

    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $value = trim($value);
        if (strlen($value) < self::MIN_LENGHT || strlen($value) > self::MAX_LENGHT) {
            throw new \InvalidArgumentException(sprintf('Description should be min %s char and max %s char.', self::MIN_LENGHT, self::MAX_LENGHT));
        }

        $this->value = $value;
    }

    public static function fromString(string $description): Description
    {
        return new self($description);
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

    public function equals(Description $other): bool
    {
        return $this->value === $other->value;
    }
}
