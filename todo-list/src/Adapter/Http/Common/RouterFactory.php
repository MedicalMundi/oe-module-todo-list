<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Adapter\Http\Common;

use League\Route\RouteGroup;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use MedicalMundi\TodoList\Adapter\Http\Web\AboutController;
use MedicalMundi\TodoList\Adapter\Http\Web\AddTodoController;
use MedicalMundi\TodoList\Adapter\Http\Web\HelpController;
use MedicalMundi\TodoList\Adapter\Http\Web\HomeController;
use MedicalMundi\TodoList\Adapter\Http\Web\ToDoListController;
use MedicalMundi\TodoList\Adapter\Http\Web\ToDoReadController;
use OpenEMR\Modules\MedicalMundiTodoList\isModuleStandAlone;
use Psr\Container\ContainerInterface;

class RouterFactory
{
    private const PREFIX_URL = '/interface/modules/custom_modules/oe-module-todo-list';

    public function __invoke(ContainerInterface $container): Router
    {
        $strategy = new ApplicationStrategy();
        $strategy->setContainer($container);
        $router = new Router();
        $router->setStrategy($strategy);

        if ((new isModuleStandAlone())()) {
            $prefix = '';
            $routerGroupTodoUrl = '/todos';
            $routerGroupModuleSettingUrl = '/module-setting';
        } else {
            $prefix = self::PREFIX_URL;
            $routerGroupTodoUrl = self::PREFIX_URL . '/todos';
            $routerGroupModuleSettingUrl = self::PREFIX_URL . '/module-setting';
        }

        $router->map('GET', $prefix . '/', HomeController::class);
        $router->map('GET', $prefix . '/about', AboutController::class);
        $router->map('GET', $prefix . '/help', HelpController::class);

        $router->group($routerGroupTodoUrl, function (RouteGroup $route): void {
            $route->map('GET', '/new', AddToDoController::class);
            $route->map('GET', '/', ToDoListController::class);
            $route->map('GET', '/{id:uuid}', ToDoReadController::class);
        });

        $router->group($routerGroupModuleSettingUrl, function (RouteGroup $route): void {
            $route->map('GET', '/', HomeController::class);
        });

        return $router;
    }
}
