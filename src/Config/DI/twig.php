<?php declare(strict_types=1);

use OpenEMR\Modules\MedicalMundiTodoList\isModuleStandAlone;
use OpenEMR\Modules\MedicalMundiTodoList\Module;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extra\String\StringExtension;
use Twig\Loader\FilesystemLoader;

$moduleProjectDir = \dirname(__DIR__, 3);

$loader = new FilesystemLoader($moduleProjectDir . "/src/Adapter/Http/Web/Template");

$twigOptions = [];

$TwigEnvironment = new Environment($loader, $twigOptions);
$TwigEnvironment->addExtension(new DebugExtension());
$TwigEnvironment->addExtension(new StringExtension());

$TwigEnvironment->addGlobal('module', [
    'name' => Module::MODULE_NAME,
    'version' => Module::MODULE_VERSION,
    'source_code' => Module::MODULE_SOURCE_CODE,
    'vendor_name' => Module::VENDOR_NAME,
    'vendor_url' => Module::VENDOR_URL,
    'isStandAlone' => (new isModuleStandAlone())(),
]);

return [
    Environment::class => $TwigEnvironment,
];
