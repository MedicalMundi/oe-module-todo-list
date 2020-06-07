<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Domain\Todo\Repository;

use MedicalMundi\TodoList\Domain\Todo\Exception\CouldNotDeleteTodo;
use MedicalMundi\TodoList\Domain\Todo\TodoId;

interface DeleteTodo
{
    /**
     * @param TodoId $todoId
     * @throws CouldNotDeleteTodo
     */
    public function deleteTodoWithId(TodoId $todoId): void;
}
