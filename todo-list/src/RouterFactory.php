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
        // /interface/modules/custom_modules/oe-module-todo-list/
        $strategy = new ApplicationStrategy();
        $strategy->setContainer($container);
        $router   = new Router();
        $router->setStrategy($strategy);

        if ((new isModuleStandAlone)()) {
            //$router->map('GET', self::PREFIX_URL, HomeController::class);
            $routerGroupUrl = '/';
        } else {
            $router->map('GET', '/', HomeController::class);
            $routerGroupUrl = self::PREFIX_URL;
        }
        $router->map('GET', $routerGroupUrl.'home', HomeController::class);

        $router->group($routerGroupUrl, function (RouteGroup $route) : void {
            $route->map('GET', '/', ToDoListController::class);
            $route->map('GET', '/{id}', ToDoReadController::class);
        });

        return $router;
    }
}
