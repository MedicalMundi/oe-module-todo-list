<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Application\Port\In;

interface AddTodoUseCase
{
    public function addTodo(AddTodoCommand $command): void;
}
