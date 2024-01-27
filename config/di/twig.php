<?php declare(strict_types=1);

use DI\Container;
use Odan\Session\SessionInterface;
use OpenEMR\Modules\MedicalMundiTodoList\Module;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extra\String\StringExtension;
use Twig\Loader\FilesystemLoader;

return [
    Environment::class => function (Container $container) {
        $loader = new FilesystemLoader(Module::mainDir() . "/src/Adapter/Http/Web/Template");

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
            'license' => Module::LICENSE,
            'license_url' => Module::LICENSE_URL,
            'isStandAlone' => Module::isStandAlone(),
        ]);

        $flash = $container->get(SessionInterface::class)->getFlash();
        $TwigEnvironment->addGlobal('flash', $flash);

        return $TwigEnvironment;
    },
];
