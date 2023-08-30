<?php declare(strict_types=1);

use Enqueue\Dbal\DbalConnectionFactory;
use MedicalMundi\TodoList\Adapter\Persistence\FileSystem\JsonTodoRepository;
use MedicalMundi\TodoList\Application\AddTodoService;
use MedicalMundi\TodoList\Application\Port\In\AddTodoUseCase;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\AddTodoPort;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\FindTodosPort;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\LoadTodoPort;
use MedicalMundi\TodoList\TodoListContext;

return [
    //persistence
//    JsonTodoRepository::class => DI\create(JsonTodoRepository::class),
//    FindTodosPort::class => DI\autowire(JsonTodoRepository::class),
//    AddTodoPort::class => DI\autowire(JsonTodoRepository::class),
//    LoadTodoPort::class => DI\autowire(JsonTodoRepository::class),

    //service
//    AddTodoService::class => DI\create(AddTodoService::class),
//    AddTodoUseCase::class => DI\autowire(AddTodoService::class),

    //db conf
    DbalConnectionFactory::class => new DbalConnectionFactory(sprintf("sqlite:////%s%s%s", TodoListContext::getModuleDir(), '/var/module_data/', TodoListContext::getSqLiteDatabaseName())),
];
