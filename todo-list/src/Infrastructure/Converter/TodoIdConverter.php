<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Infrastructure\Converter;

use Ecotone\Messaging\Attribute\Converter;
use MedicalMundi\TodoList\Application\Domain\Todo\TodoId;

class TodoIdConverter
{
    #[Converter]
    public function convertFrom(TodoId $todoId): string
    {
        return $todoId->toString();
    }

    #[Converter]
    public function convertTo(string $todoId): TodoId
    {
        return TodoId::fromString($todoId);
    }
}
