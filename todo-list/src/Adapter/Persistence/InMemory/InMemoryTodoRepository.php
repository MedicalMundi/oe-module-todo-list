<?php

declare(strict_types=1);

namespace MedicalMundi\TodoList\Adapter\Persistence\InMemory;

use Doctrine\Common\Collections\ArrayCollection;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\AddTodoPort;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\FindTodosPort;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\LoadTodoPort;
use MedicalMundi\TodoList\Domain\Todo\Exception\CouldNotRetrieveTodo;
use MedicalMundi\TodoList\Domain\Todo\Exception\CouldNotSaveTodo;
use MedicalMundi\TodoList\Domain\Todo\Todo;
use MedicalMundi\TodoList\Domain\Todo\TodoId;

class InMemoryTodoRepository implements AddTodoPort, LoadTodoPort, FindTodosPort
{
    /**
     * @var ArrayCollection<string, Todo>
     */
    private $todos;

    public function __construct()
    {
        $this->todos = new arrayCollection();
    }

    private function containsKey(TodoId $todoId): bool
    {
        return $this->todos->containsKey($todoId->toString());
    }

    /**
     * @throws CouldNotSaveTodo
     */
    public function addTodo(Todo $todo): void
    {
        if ($this->containsKey($todo->id())) {
            throw CouldNotSaveTodo::becauseDuplicateTodoIdDetected($todo->id()->toString());
        }

        try {
            $this->todos->set($todo->id()->toString(), $todo);
        } catch (\Exception $exception) {
            throw CouldNotSaveTodo::becauseDatabaseError(0, $exception);
        }
    }

    /**
     * @throws CouldNotRetrieveTodo
     */
    public function withTodoId(TodoId $todoId): Todo
    {
        if (! $todo = $this->todos->get($todoId->toString())) {
            throw CouldNotRetrieveTodo::becauseTodoNotExist($todoId->toString());
        }

        return $todo;
    }

    /**
     * @return Todo[]
     */
    public function findTodos(): array
    {
        return $this->todos->getValues();
    }
}
