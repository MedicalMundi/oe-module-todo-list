<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Domain\Todo\Repository;

use MedicalMundi\TodoList\Domain\Todo\Exception\CouldNotRetrieveTodo;
use MedicalMundi\TodoList\Domain\Todo\Todo;
use MedicalMundi\TodoList\Domain\Todo\TodoId;

interface LoadTodo
{
    /**
     * @throws CouldNotRetrieveTodo
     */
    public function withTodoId(TodoId $todoId): Todo;
}
