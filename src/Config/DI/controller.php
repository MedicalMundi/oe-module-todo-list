<?php declare(strict_types=1);

use MedicalMundi\TodoList\Adapter\Persistence\FileSystem\JsonTodoRepository;
use MedicalMundi\TodoList\Application\AddTodoService;
use MedicalMundi\TodoList\Application\Port\In\AddTodoUseCase;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\AddTodoPort;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\FindTodosPort;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\LoadTodoPort;

return [
    JsonTodoRepository::class => DI\create(JsonTodoRepository::class),
    FindTodosPort::class => DI\autowire(JsonTodoRepository::class),
    AddTodoPort::class => DI\autowire(JsonTodoRepository::class),
    //new JsonTodoRepository(),
    LoadTodoPort::class => DI\autowire(JsonTodoRepository::class),
    AddTodoService::class => DI\create(AddTodoService::class),
    AddTodoUseCase::class => DI\autowire(AddTodoService::class),
];
