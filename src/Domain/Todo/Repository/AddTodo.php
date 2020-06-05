<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Domain\Todo\Repository;

use MedicalMundi\TodoList\Domain\Todo\Exception\CouldNotSaveTodo;
use MedicalMundi\TodoList\Domain\Todo\Todo;

interface AddTodo
{
    /**
     * @param Todo $todo
     * @throws CouldNotSaveTodo
     */
    public function addTodo(Todo $todo): void;
}
