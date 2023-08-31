<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Common;

use League\Route\RouteGroup;
use League\Route\Router;
use League\Route\Strategy\ApplicationStrategy;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web\AboutController;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web\AddTodoController;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web\DashboardController;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web\HelpController;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web\HomeController;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web\MessagesController;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web\ToDoListController;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web\ToDoReadController;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\WebApi\Todo\WapiDeleteTodoController;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\WebApi\Todo\WapiPostTodoController;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\WebApi\Todo\WapiShowTodoController;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\WebApi\Todo\WapiToDoListController;
use OpenEMR\Modules\MedicalMundiTodoList\Module;
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

        if (Module::isStandAlone()) {
            $prefix = '';
            $webTodoRouterGroup = '/todos';
            $webApiTodoRouterGroup = '/wapi/todos';
            $webModuleSettingrouterGroup = '/module-setting';
        } else {
            $prefix = self::PREFIX_URL;
            $webTodoRouterGroup = self::PREFIX_URL . '/todos';
            $webApiTodoRouterGroup = self::PREFIX_URL . '/wapi/todos';
            $webModuleSettingrouterGroup = self::PREFIX_URL . '/module-setting';
        }

        $router->map('GET', $prefix . '/', HomeController::class);
        $router->map('GET', $prefix . '/dashboard', DashboardController::class);
        $router->map('GET', $prefix . '/about', AboutController::class);
        $router->map('GET', $prefix . '/help', HelpController::class);
        $router->map('GET', $prefix . '/messages', MessagesController::class);

        $router->group($webTodoRouterGroup, function (RouteGroup $route): void {
            $route->map('GET', '/new', AddToDoController::class);
            $route->map('POST', '/new', AddToDoController::class);
            $route->map('GET', '/', ToDoListController::class);
            $route->map('GET', '/{id:uuid}', ToDoReadController::class);
        });

        $router->group($webApiTodoRouterGroup, function (RouteGroup $route): void {
            $route->map('GET', '/', WapiToDoListController::class);
            $route->map('POST', '/', WapiPostTodoController::class);
            $route->map('GET', '/{id:uuid}', WapiShowTodoController::class);
            // TODO implement in domain
            //$route->map('DELETE', '/{id:uuid}', WapiDeleteTodoController::class);
        });

        $router->group($webModuleSettingrouterGroup, function (RouteGroup $route): void {
            $route->map('GET', '/', HomeController::class);
        });

        return $router;
    }
}
