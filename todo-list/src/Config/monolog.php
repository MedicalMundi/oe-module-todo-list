<?php declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Reference;

$container->register(StreamHandler::class, StreamHandler::class)
    ->addArgument(__DIR__.'/../../../var/log/module.log');

$container->register(LoggerInterface::class, Logger::class)
    ->addArgument('oe-module-todo-list')
    ->addMethodCall('pushHandler', [new Reference(StreamHandler::class)]);
