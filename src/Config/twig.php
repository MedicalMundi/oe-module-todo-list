<?php declare(strict_types=1);

use OpenEMR\Modules\MedicalMundiTodoList\isModuleStandAlone;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extra\String\StringExtension;
use Twig\Loader\FilesystemLoader;

$moduleDir = \dirname(__DIR__, 2);

$loader = new FilesystemLoader($moduleDir . "/src/Adapter/Http/Web/Template");

$twigOptions = [];

// configure cache and add to $twigOptions

// configure debug mode and add to $twigOptions

$TwigEnvironment = new Environment($loader, $twigOptions);


// add twig extension if needed

// i18n for twig
//$TwigEnvironment->addExtension(new \Twig_Extensions_Extension_I18n());

// should be optional
$TwigEnvironment->addExtension(new DebugExtension());
$TwigEnvironment->addExtension(new StringExtension());

$TwigEnvironment->addGlobal('module', [
    'name' => 'Todo List',
    'version' => 'v0.1.0',
    'isStandAlone' => (new isModuleStandAlone())(),
]);



$container->set(Environment::class, $TwigEnvironment);
//->register('Twig\Environment', $TwigEnvironment)
//->setPublic(true)
;
