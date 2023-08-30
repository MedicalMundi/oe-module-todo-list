<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Application\Domain\Todo\Event;

use MedicalMundi\TodoList\Application\Domain\Todo\TodoId;

class TodoWasPosted
{
    public function __construct(
        public TodoId $todoId
    ) {
    }
}
