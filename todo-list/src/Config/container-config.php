<?php declare(strict_types=1);

use MedicalMundi\TodoList\Adapter\Persistence\FileSystem\JsonTodoRepository;
use MedicalMundi\TodoList\Adapter\Persistence\InMemory\InMemoryTodoRepository;
use MedicalMundi\TodoList\Application\AddTodoService;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Common\UrlService;
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
//    ->setBindings([
//        //LoadTodoPort::class.' $repository', new Reference(InMemoryTodoRepository::class),
//        LoadTodoPort::class, new Reference(InMemoryTodoRepository::class)
//    ])
;

// all classes that implement the WebController interface will receive a tag 'module.web_controller'
$container->registerForAutoconfiguration(WebController::class)
    ->addTag('module.web_controller');


// classes in the namespace MedicalMundi\TodoList\ and the directory todo-list/src/ will be register in the container
/** @var PhpFileLoader $loader */
$loader->registerClasses(
    $privateDefinition,
    'MedicalMundi\\TodoList\\',
    '../../src/*',
    '../../src/{Config,Entity,DependencyInjection,Adapter,Module.php,RouterFactory.php,*Controller.php}'
);

// classes in the namespace MedicalMundi\TodoList\Adapter\Http\Web\ and the directory src/ will be register in the container
$loader->registerClasses(
    $publicDefinition,
    'MedicalMundi\\TodoList\\Adapter\\Http\\Web\\',
    '../../src/Adapter/Http/Web/*',
    '../../src/Adapter/Http/Web/{WebController.php}'
);

// classes in the namespace MedicalMundi\TodoList\Adapter\Http\Web\ and the directory src/ will be register in the container
$loader->registerClasses(
    $publicDefinition,
    'MedicalMundi\\TodoList\\Adapter\\Http\\Common\\',
    '../../src/Adapter/Http/Common/*'
    //'../../src/Adapter/Http/Common/{WebController.php}'
);

//bind:
//Medicalmundi\Publishing\Application\Port\In\DraftArticleUseCase $draftArticleService: '@Medicalmundi\Publishing\Application\DraftArticleService'
//            Medicalmundi\Publishing\Application\Port\In\ListArticlesUseCase $listArticleService: '@Medicalmundi\Publishing\Application\ListArticlesService'
//            Medicalmundi\Publishing\Application\Port\In\ShowArticleUseCase $showArticleService: '@Medicalmundi\Publishing\Application\ShowArticleService'

//$container->alias(LoadTodoPort::class.' $repository', InMemoryTodoRepository::class);

// override the services to set the configurator
//$services->set(NewsletterManager::class)
//    ->configurator(ref(EmailConfigurator::class), 'configure');




$container
    ->register(ToDoReadController::class, ToDoReadController::class)
    ->addArgument(new Reference(JsonTodoRepository::class))
    //->addArgument(new Reference('MedicalMundi\TodoList\Adapter\Persistence\InMemory\InMemoryTodoRepository'))
    ->addArgument(new Reference(UrlService::class))
    ->addArgument(new Reference(Environment::class))
    ->setPublic(true)
;

$container
    ->register(ToDoListController::class, ToDoListController::class)
    ->addArgument(new Reference(JsonTodoRepository::class))
    //->addArgument(new Reference('MedicalMundi\TodoList\Adapter\Persistence\InMemory\InMemoryTodoRepository'))
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
