<?php declare(strict_types=1);


namespace MedicalMundi\TodoList;

use League\Route\RouteGroup;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use Psr\Container\ContainerInterface;

class RouterFactory
{
    public function __invoke(ContainerInterface $container): Router
    {
        $strategy = new ApplicationStrategy();
        $strategy->setContainer($container);
        $router   = new Router();
        $router->setStrategy($strategy);

        $router->map('GET', '/', HomeController::class);
        $router->group('/todos', function (RouteGroup $route) : void {
            $route->map('GET', '/', ToDoListController::class);
            $route->map('GET', '/{id}', ToDoReadController::class);
        });

        return $router;
    }
}
