<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList;

use DI\ContainerBuilder;
use Ecotone\Lite\EcotoneLite;
use Ecotone\Lite\EcotoneLiteApplication;
use Ecotone\Messaging\Config\ConfiguredMessagingSystem;
use Ecotone\Messaging\Config\ServiceConfiguration;
use Ecotone\Messaging\Store\Document\DocumentStore;
use Ecotone\Modelling\CommandBus;
use Ecotone\Modelling\QueryBus;
use Enqueue\Dbal\DbalConnectionFactory;
use League\Route\Http\Exception as HttpException;
use League\Route\Router;
use Nyholm\Psr7\Factory\Psr17Factory;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Common\RouterFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Module
{
    final public const MODULE_NAME = 'ToDo List';

    final public const MODULE_VERSION = 'v0.2.1';

    final public const MODULE_SOURCE_CODE = 'https://github.com/MedicalMundi/oe-module-todo-list';

    final public const VENDOR_NAME = 'MedicalMundi';

    final public const VENDOR_URL = 'https://github.com/MedicalMundi';

    final public const LICENSE = 'GPL-3.0';

    final public const LICENSE_URL = 'https://github.com/MedicalMundi/oe-module-todo-list/blob/main/LICENSE';

    protected ?ContainerInterface $container = null;

    private ?Router $router = null;

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     *
     * @psalm-suppress MixedInferredReturnType
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        try {
            /** @psalm-suppress PossiblyNullReference */
            $response = $this->router->dispatch($request);
            //} catch (InvalidDataException $e) {
            //return new JsonResponse([ 'error' => exception_to_array($e) ], 400);
        } catch (HttpException $e) {
            $responseBody = $psr17Factory->createStream(json_encode([
                'error' => $e->getMessage(),
            ], JSON_THROW_ON_ERROR));
            return $psr17Factory->createResponse($e->getStatusCode())->withBody($responseBody);
        }

        return $response;
    }

    private function buildContainer(): ContainerInterface
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAutowiring(true);
        $containerBuilder->useAttributes(true);
        $containerBuilder->addDefinitions(__DIR__ . '/../config/di/monolog.php');
        $containerBuilder->addDefinitions(__DIR__ . '/../config/di/twig.php');
        $containerBuilder->addDefinitions(__DIR__ . '/../config/di/controller.php');
        $containerBuilder->addDefinitions(__DIR__ . '/../config/di/ecotone.php');

        return $containerBuilder->build();
    }

    public static function bootstrap(): self
    {
        $module = new self();
        $container = $module->buildContainer();
        $module->bootstrapMessagingSystemLite($container);
        $router = (new RouterFactory())($container);
        $module->container = $container;
        $module->router = $router;

        return $module;
    }

    public static function bootstrapForConsole(): ConfiguredMessagingSystem
    {
        return (new self())->bootstrapMessagingSystemApplication();
    }

    public static function bootstrapWithEcotoneLiteApplication(): self
    {
        $module = new self();

        $configuredMessagingSystem = $module->bootstrapMessagingSystemApplication();

        $containerBuilder = new ContainerBuilder();
        $containerBuilder->useAutowiring(true);
        $containerBuilder->useAttributes(true);
        $containerBuilder->addDefinitions(__DIR__ . '/../config/di/monolog.php');
        $containerBuilder->addDefinitions(__DIR__ . '/../config/di/twig.php');
        $containerBuilder->addDefinitions(__DIR__ . '/../config/di/controller.php');
        $containerBuilder->addDefinitions([
            CommandBus::class => $configuredMessagingSystem->getCommandBus(),
            QueryBus::class => $configuredMessagingSystem->getQueryBus(),
            DocumentStore::class => $configuredMessagingSystem->getServiceFromContainer(DocumentStore::class),
        ]);

        $container = $containerBuilder->build();

        $router = (new RouterFactory())($container);
        $module->container = $container;
        $module->router = $router;

        return $module;
    }

    private static function bootstrapMessagingSystemLite(ContainerInterface $container = null): ConfiguredMessagingSystem
    {
        $container ??= (new self())->buildContainer();
        $rootCatalog = realpath(__DIR__ . '/..');

        $serviceConfiguration = ServiceConfiguration::createWithDefaults()
            ->withLoadCatalog($rootCatalog . '/src')
            ->withNamespaces(['MedicalMundi']);

        return EcotoneLite::bootstrap(
            classesToResolve: [DbalConnectionFactory::class],
            containerOrAvailableServices: $container,
            configuration: $serviceConfiguration,
            configurationVariables: [],
            useCachedVersion: false,
            pathToRootCatalog: $rootCatalog . '/src',
            allowGatewaysToBeRegisteredInContainer: true
        );
    }

    private static function bootstrapMessagingSystemApplication(): ConfiguredMessagingSystem
    {
        $rootCatalog = realpath(__DIR__ . '/..');

        $serviceConfiguration = ServiceConfiguration::createWithDefaults()
            ->withLoadCatalog($rootCatalog . '/src')
            ->withNamespaces(['MedicalMundi']);

        return EcotoneLiteApplication::bootstrap(
            objectsToRegister: [
            ],
            configurationVariables: [],
            serviceConfiguration: $serviceConfiguration,
            cacheConfiguration: false,
            pathToRootCatalog: $rootCatalog . '/src',
            classesToRegister: []
        );
    }

    public static function isStandAlone(): bool
    {
        $interfaceRootDirectory = \dirname(__DIR__, 4);
        $openemrGlobalFile = $interfaceRootDirectory . DIRECTORY_SEPARATOR . "globals.php";
        return ! file_exists($openemrGlobalFile);
    }

    public static function mainDir(): string
    {
        return \dirname(__DIR__, 1);
    }

    public static function openemrDir(): string
    {
        return \dirname(__DIR__, 5);
    }

    public function getContainer(): ?ContainerInterface
    {
        return $this->container;
    }
}
