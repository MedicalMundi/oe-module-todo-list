<?php declare(strict_types=1);


namespace MedicalMundi\TodoList;

use League\Route\RouteGroup;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Psr\Container\ContainerInterface;

class RouterFactory
{
    private const PREFIX_URL = '/interface/modules/custom_modules/oe-module-todo-list/';

    public function __invoke(ContainerInterface $container): Router
    {
        $strategy = new ApplicationStrategy();
        $strategy->setContainer($container);
        $router   = new Router();
        $router->setStrategy($strategy);

        if ((new isModuleStandAlone)()) {
            //$router->map('GET', self::PREFIX_URL, HomeController::class);
            $routerGroupdefaultUrl = '/';
            $routerGroupTodoUrl = '/todos';
            $routerGroupModuleSettingUrl = '/module-setting';
        } else {
            //$router->map('GET', '/', HomeController::class);
            $routerGroupdefaultUrl = self::PREFIX_URL;
            $routerGroupTodoUrl = self::PREFIX_URL . '/todos';
            $routerGroupModuleSettingUrl = self::PREFIX_URL . '/module-setting';
        }
        //$router->map('GET', $routerGroupUrl.'about', AboutController::class);

        $router->group($routerGroupdefaultUrl, function (RouteGroup $route) : void {
            $route->map('GET', '/', HomeController::class);
            $route->map('GET', '/about', AboutController::class);
            $route->map('GET', '/help', AboutController::class);
        });

        $router->group($routerGroupTodoUrl, function (RouteGroup $route) : void {
            $route->map('GET', '/', ToDoListController::class);
            $route->map('GET', '/{id:number}', ToDoReadController::class);
        });



        return $router;
    }
}
