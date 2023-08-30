<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Application\Domain\Todo\Command;

class PostTodo
{
    public function __construct(
        public string $todoId,
        public string $todoTitle
    ) {
    }
}
