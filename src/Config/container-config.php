<?php declare(strict_types=1);

use MedicalMundi\TodoList\Adapter\Persistence\FileSystem\JsonTodoRepository;
use MedicalMundi\TodoList\Adapter\Persistence\InMemory\InMemoryTodoRepository;
use MedicalMundi\TodoList\Application\AddTodoService;
use MedicalMundi\TodoList\Application\Port\In\AddTodoUseCase;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Common\UrlService;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web\AboutController;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web\DashboardController;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web\HelpController;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web\HomeController;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web\ToDoListController;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web\ToDoReadController;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web\WebController;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Twig\Environment;

$privateDefinition = new Definition();
$privateDefinition
    ->setAutowired(true)
    ->setAutoconfigured(true)
    ->setPublic(false);

$publicDefinition = new Definition();
$publicDefinition
    ->setAutowired(true)
    ->setAutoconfigured(true)
    ->setPublic(true)
;

// all classes that implement the WebController interface will receive a tag 'module.web_controller'
$container->registerForAutoconfiguration(WebController::class)
    ->addTag('module.web_controller');


/**
 * Register all module classes as service in container
 *
 */
/** @var PhpFileLoader $loader */
$loader->registerClasses(
    $privateDefinition,
    'OpenEMR\\Modules\\MedicalMundiTodoList\\',
    '../../src/*',
    '../../src/{Config,Module.php}'
);

/**
 * Register module http controller classes as service in container
 */
$loader->registerClasses(
    $publicDefinition,
    'OpenEMR\\Modules\\MedicalMundiTodoList\\Adapter\\Http\\Web\\',
    '../../src/Adapter/Http/Web/*',
    '../../src/Adapter/Http/Web/{WebController.php}'
);

/**
 * Register module http common classes as service in container
 */
$loader->registerClasses(
    $publicDefinition,
    'OpenEMR\\Modules\\MedicalMundiTodoList\\Adapter\\Http\\Common\\',
    '../../src/Adapter/Http/Common/*'
);

/**
 * Register application domain classes in container
 */
$loader->registerClasses(
    $publicDefinition,
    'MedicalMundi\\TodoList\\',
    '../../todo-list/src/*',
    [
        '../../todo-list/src/Domain/',
        '../../todo-list/src/Application/Port/In/',
    ]
);

$container
    ->register(ToDoReadController::class, ToDoReadController::class)
    ->addArgument(new Reference(JsonTodoRepository::class))
    ->addArgument(new Reference(UrlService::class))
    ->addArgument(new Reference(Environment::class))
    ->setPublic(true)
;

$container
    ->register(ToDoListController::class, ToDoListController::class)
    ->addArgument(new Reference(JsonTodoRepository::class))
    ->addArgument(new Reference(UrlService::class))
    ->addArgument(new Reference(Environment::class))
    ->setPublic(true)
;

$container
    ->register(AboutController::class, AboutController::class)
    ->addArgument(new Reference(UrlService::class))
    ->addArgument(new Reference(Environment::class))
    ->setPublic(true)
;

$container
    ->register(HomeController::class, HomeController::class)
    ->addArgument(new Reference(UrlService::class))
    ->addArgument(new Reference(Environment::class))
    ->setPublic(true)
;

$container
    ->register(HelpController::class, HelpController::class)
    ->addArgument(new Reference(UrlService::class))
    ->addArgument(new Reference(Environment::class))
    ->setPublic(true)
;

$container
    ->register(DashboardController::class, DashboardController::class)
    ->addArgument(new Reference(UrlService::class))
    ->addArgument(new Reference(Environment::class))
    ->setPublic(true)
;

$container
    ->register(InMemoryTodoRepository::class, InMemoryTodoRepository::class)
    ->setPublic(true)
;

$container
    ->register(JsonTodoRepository::class, JsonTodoRepository::class)
    ->setPublic(true)
;

$container
    ->register(AddTodoService::class, AddTodoService::class)
    ->addArgument(new Reference(InMemoryTodoRepository::class))
    ->addArgument(new Reference(LoggerInterface::class))
    ->setPublic(true);

$container->setAlias(AddTodoUseCase::class, AddTodoService::class);
