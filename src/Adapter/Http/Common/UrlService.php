<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Common;

use OpenEMR\Modules\MedicalMundiTodoList\Module;

class UrlService
{
    private const MODULE_MAIN_URL = '/interface/modules/custom_modules/oe-module-todo-list/';

    private readonly bool $isStandAloneMode;

    private readonly string $baseUrl;

    /**
     * @var array <string>
     */
    private array $routes = [
        'main',
        'about',
        'settings',
        'todo-list',
        'todo',
        'help',
        'dashboard',
    ];

    public function __construct(?bool $isStandAloneMode = null)
    {
        $this->isStandAloneMode = $isStandAloneMode ?? Module::isStandAlone();

        $this->baseUrl = (true === $this->isStandAloneMode) ? '/' : self::MODULE_MAIN_URL;
    }

    public function baseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @param array <int,string> $arguments
     */
    public function renderUrl(string $routeName, array $arguments = []): string
    {
        if (! in_array($routeName, $this->routes)) {
            throw new \InvalidArgumentException('Route with name: ' . $routeName . ' not found.');
        }

        if ($routeName === 'todo-list') {
            $routeName = 'todoList';
        }

        return (string) $this->$routeName();
    }

    private function main(): string
    {
        return $this->baseUrl;
    }

    private function about(): string
    {
        return $this->baseUrl . 'about';
    }

    private function help(): string
    {
        return $this->baseUrl . 'help';
    }

    private function todoList(): string
    {
        return $this->baseUrl . 'todos';
    }

    private function settings(): string
    {
        return $this->baseUrl . 'settings';
    }

    private function dashboard(): string
    {
        return $this->baseUrl . 'dashboard';
    }
}
