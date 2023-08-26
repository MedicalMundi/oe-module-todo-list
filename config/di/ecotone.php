<?php declare(strict_types=1);

use Enqueue\Dbal\DbalConnectionFactory;
use MedicalMundi\TodoList\TodoListContext;
use OpenEMR\Modules\MedicalMundiTodoList\Module;

if (! Module::isStandAlone()) {
    if (file_exists($openemrSqlconfFile = Module::openemrDir() . DIRECTORY_SEPARATOR . 'sites/default/sqlconf.php')) {
        /**
         * TODO: find a better way to get db params from openemr
         */
        require_once $openemrSqlconfFile;
        $ecotoneDbalSettinges = [
            'connection' => [
                'dbname' => $sqlconf['dbase'],
                'user' => $sqlconf['login'],
                'password' => $sqlconf['pass'],
                'host' => $sqlconf['host'],
                'driver' => 'pdo_mysql',
            ],
            'table_name' => 'oe_module_todo_list_enqueue',
        ];
        return [
            DbalConnectionFactory::class => new DbalConnectionFactory($ecotoneDbalSettinges),
        ];
    }
} else {
    return [
        DbalConnectionFactory::class => new DbalConnectionFactory(sprintf("sqlite:////%s%s%s", TodoListContext::getModuleDir(), '/var/module_data/', TodoListContext::getSqLiteDatabaseName())),
    ];
}
