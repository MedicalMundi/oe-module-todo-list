<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Application;

use MedicalMundi\TodoList\Application\Port\In\AddTodoCommand;
use MedicalMundi\TodoList\Application\Port\In\AddTodoUseCase;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\AddTodoPort;
use MedicalMundi\TodoList\Domain\Todo\Todo;

class AddTodoService implements AddTodoUseCase
{
    /** @var AddTodoPort */
    private $todoStorageService;

    /**
     * AddTodoService constructor.
     * @param AddTodoPort $todoStorageService
     */
    public function __construct(AddTodoPort $todoStorageService)
    {
        $this->todoStorageService = $todoStorageService;
    }

    /**
     * @param AddTodoCommand $command
     */
    public function addTodo(AddTodoCommand $command): void
    {
        $todo = new Todo($command->todoId(), $command->title());

        $this->todoStorageService->addTodo($todo);
    }
}
