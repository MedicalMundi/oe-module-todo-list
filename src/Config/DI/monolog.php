<?php declare(strict_types=1);

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use OpenEMR\Modules\MedicalMundiTodoList\isModuleStandAlone;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Reference;

if ((new IsModuleStandAlone())()) {
    /**
     * Logging in module 'var' directory when the module
     * is executed as stand-alone mode
     */
    return [
        Psr\Log\LoggerInterface::class => DI\factory(function () {
            $logger = new Logger('module-todo-list');

            $fileHandler = new StreamHandler(__DIR__ . '/../../var/log/module.log', Logger::DEBUG);
            $fileHandler->setFormatter(new LineFormatter());
            $logger->pushHandler($fileHandler);

            return $logger;
        }),
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
        Psr\Log\LoggerInterface::class => DI\factory(function () {
            $logger = new Logger('module-todo-list');

            $fileHandler = new StreamHandler(__DIR__ . '/../../var/log/module.log', Logger::DEBUG);
            $fileHandler->setFormatter(new LineFormatter());
            $logger->pushHandler($fileHandler);

            return $logger;
        }),
    ];
}
