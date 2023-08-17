<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Application;

use MedicalMundi\TodoList\Application\Port\In\AddTodoCommand;
use MedicalMundi\TodoList\Application\Port\In\AddTodoUseCase;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\AddTodoPort;
use MedicalMundi\TodoList\Domain\Todo\Todo;
use Psr\Log\LoggerInterface;

class AddTodoService implements AddTodoUseCase
{
    /**
     * @var AddTodoPort
     */
    private $todoStorageService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(AddTodoPort $todoStorageService, LoggerInterface $logger)
    {
        $this->todoStorageService = $todoStorageService;
        $this->logger = $logger;
    }

    public function addTodo(AddTodoCommand $command): void
    {
        try {
            $todo = new Todo($command->todoId(), $command->title());
            $this->todoStorageService->addTodo($todo);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}
