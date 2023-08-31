<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Application\Domain\Todo\Command;

class DeleteTodo
{
    public function __construct(
        public string $todoId,
    ) {
    }
}
