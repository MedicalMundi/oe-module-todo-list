<?php declare(strict_types=1);

namespace MedicalMundi\TodoList;

class TodoListContext
{
    public static function getModuleDir(): string
    {
        return \dirname(__DIR__, 2);
    }

    public static function getSqLiteDatabaseName(): string
    {
        return 'db_om_todo_list.sqlite';
    }
}
