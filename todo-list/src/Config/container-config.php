<?php declare(strict_types=1);

use MedicalMundi\TodoList\Adapter\Http\Web\WebController;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

$privateDefinition = new Definition();
$privateDefinition
    ->setAutowired(true)
    ->setAutoconfigured(true)
    ->setPublic(false);

$publicDefinition = new Definition();
$publicDefinition
    ->setAutowired(true)
    ->setAutoconfigured(true)
    ->setPublic(true);

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
    '../../src/Adapter/Http/Common/*',
    '../../src/Adapter/Http/Common/{WebController.php}'
);
