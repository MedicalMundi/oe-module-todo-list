<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Application\Domain\Todo;

class TodoStatus
{
    private const OPEN = 'open';

    private const DONE = 'done';

    private const EXPIRED = 'expired';

    private function __construct(
        private string $value
    ) {
    }

    public static function OPEN()
    {
        return new self(self::OPEN);
    }

    public static function DONE()
    {
        return new self(self::DONE);
    }

    public static function EXPIRED()
    {
        return new self(self::EXPIRED);
    }

    public function value(): string
    {
        return $this->value;
    }
}
