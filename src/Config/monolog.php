<?php declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use OpenEMR\Modules\MedicalMundiTodoList\isModuleStandAlone;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Reference;

if ((new IsModuleStandAlone())()) {
    // Logging in module 'var' directory
    $container->register(StreamHandler::class, StreamHandler::class)
        ->addArgument(__DIR__ . '/../../var/log/module.log');
} else {
    // Logging in openemr 'var' directory
    // TODO: change path, use openemr var dir
    $container->register(StreamHandler::class, StreamHandler::class)
        ->addArgument(__DIR__ . '/../../module.log');
}

$container->register(LoggerInterface::class, Logger::class)
    ->addArgument('oe-module-todo-list')
    ->addMethodCall('pushHandler', [new Reference(StreamHandler::class)]);
