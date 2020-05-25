<?php declare(strict_types=1);


namespace MedicalMundi\TodoList;

class UrlService
{
    private const MODULE_MAIN_URL = '/interface/modules/custom_modules/oe-module-todo-list/';

    /** @var bool  */
    private $isStandAloneMode;

    /** @var string */
    private $baseUrl;

    /** @var array <string> */
    private $routes = [
        'main',
        'about',
        'settings',
        'todo-list',
        'todo'
    ];

    /**
     * UrlService constructor.
     * @param bool $isStandAloneMode
     */
    public function __construct(?bool $isStandAloneMode=null)
    {
        $this->isStandAloneMode = (null === $isStandAloneMode) ? (bool) (new isModuleStandAlone)() : $isStandAloneMode;

        $this->baseUrl = (true === $this->isStandAloneMode) ? '/' : self::MODULE_MAIN_URL;
    }

    public function baseUrl(): string
    {
        return $this->baseUrl;
    }


    /**
     * @param string $routeName
     * @param array <int,string> $arguments
     * @return string
     */
    public function renderUrl(string $routeName, array $arguments=[]): string
    {
        if (!in_array($routeName, $this->routes)) {
            throw new \InvalidArgumentException('Route with name: '. $routeName . 'not found.');
        }

        if ($routeName === 'todo-list') {
            $routeName = 'todoList';
        }

        return (string) $this->$routeName();
    }

    private function main(): string
    {
        return $this->baseUrl.'main';
    }

    private function about(): string
    {
        return $this->baseUrl.'about';
    }

    private function settings(): string
    {
        return $this->baseUrl.'settings';
    }

    private function todoList(): string
    {
        return $this->baseUrl.'todo-list';
    }
}
