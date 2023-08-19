<?php declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use OpenEMR\Modules\MedicalMundiTodoList\isModuleStandAlone;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Reference;

if ((new IsModuleStandAlone())()) {
    /**
     * Logging in module 'var' directory when the module
     * is executed as stand alone mode
     */
    $container->register(StreamHandler::class, StreamHandler::class)
        ->addArgument(__DIR__ . '/../../var/log/module.log');
} else {
    /**
     * Logging in epenemr 'Documents' directory when the module
     * is executed inside openemr
     * TODO: change path, use openemr Documents dir
     *      or change module folder permission after installation
     *      chown apache:root -R interface/modules/custom_modules/oe-module-todo-list/
     */
    $container->register(StreamHandler::class, StreamHandler::class)
        ->addArgument(__DIR__ . '/../../module.log');
}

$container->register(LoggerInterface::class, Logger::class)
    ->addArgument('oe-module-todo-list')
    ->addMethodCall('pushHandler', [new Reference(StreamHandler::class)]);
