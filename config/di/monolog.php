<?php declare(strict_types=1);

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use OpenEMR\Modules\MedicalMundiTodoList\Module;
use Psr\Log\LoggerInterface;

if (Module::isStandAlone()) {
    /**
     * Logging in module 'var' directory when the module
     * is executed as stand-alone mode
     */
    return [
        Logger::class => DI\factory(function () {
            $logger = new Logger('module-todo-list');
            $fileHandler = new StreamHandler(Module::mainDir() . '/var/log/module.log', Logger::DEBUG);
            $fileHandler->setFormatter(new LineFormatter());
            $logger->pushHandler($fileHandler);

            return $logger;
        }),

        LoggerInterface::class => DI\get(Logger::class),

        'logger' => DI\get(LoggerInterface::class),
    ];
} else {
    /**
     * Logging in epenemr 'Documents' directory when the module
     * is executed inside openemr
     * TODO: change path, use openemr Documents dir
     *      or change module folder permission after installation
     *      chown apache:root -R interface/modules/custom_modules/oe-module-todo-list/
     */
    return [
        Logger::class => DI\factory(function () {
            $logger = new Logger('module-todo-list');
            $fileHandler = new StreamHandler(Module::mainDir() . '/var/log/module.log', Logger::DEBUG);
            $fileHandler->setFormatter(new LineFormatter());
            $logger->pushHandler($fileHandler);

            return $logger;
        }),

        LoggerInterface::class => DI\get(Logger::class),

        'logger' => DI\get(LoggerInterface::class),
    ];
}
