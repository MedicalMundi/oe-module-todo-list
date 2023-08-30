<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Infrastructure\Converter;

use Ecotone\Messaging\Attribute\Converter;
use MedicalMundi\TodoList\Application\Domain\Todo\TodoStatus;

class TodoStatusConverter
{
    #[Converter]
    public function convertFrom(TodoStatus $todoStatus): string
    {
        return $todoStatus->value();
    }

    #[Converter]
    public function convertTo(string $todoStatus): TodoStatus
    {
        // TODO improve conversion..
        if (TodoStatus::OPEN()->value() === $todoStatus) {
            return TodoStatus::OPEN();
        } elseif (TodoStatus::DONE()->value() === $todoStatus) {
            return TodoStatus::DONE();
        } else {
            return TodoStatus::EXPIRED();
        }
    }
}
