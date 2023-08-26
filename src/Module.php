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
use MedicalMundi\TodoList\TodoListContext;
use Nyholm\Psr7\Factory\Psr17Factory;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Common\RouterFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Module implements ContainerInterface, RequestHandlerInterface
{
    public const MODULE_NAME = 'ToDo List';

    public const MODULE_VERSION = 'v0.1.6';

    public const MODULE_SOURCE_CODE = 'https://github.com/MedicalMundi/oe-module-todo-list';

    public const VENDOR_NAME = 'MedicalMundi';

    public const VENDOR_URL = 'https://github.com/MedicalMundi';

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
        $containerBuilder->addDefinitions(__DIR__ . '/Config/DI/monolog.php');
        $containerBuilder->addDefinitions(__DIR__ . '/Config/DI/twig.php');
        $containerBuilder->addDefinitions(__DIR__ . '/Config/DI/controller.php');
        //var_dump(Module::openemrDir(). DIRECTORY_SEPARATOR . 'sites/default/sqlconf.php');
        if (! ((new isModuleStandAlone())())) {
            //var_dump(Module::openemrDir());
            // 1 READ CONFIG VALUES FROM DEFAULT FILE
            if (file_exists($file = Module::openemrDir() . DIRECTORY_SEPARATOR . 'sites/default/sqlconf.php')) {
                require_once $file;
                //var_dump($sqlconf);
                // 2 PREPARE CUSTOM ARRAY FOR ECOTONE DBAL CONN.
                $ecotoneDbalSettinges = [
                    'connection' => [
                        'dbname' => $sqlconf['dbase'],
                        'user' => $sqlconf['login'],
                        'password' => $sqlconf['pass'],
                        'host' => $sqlconf['host'],
                        'driver' => 'pdo_mysql',//$sqlconf['pdo_mysql'],
                    ],
                    'table_name' => 'oe_module_todo_list_enqueue',
                ];
                // 3 CREATE ECOTONE DBAL CONN.

                $dbalConnection = new DbalConnectionFactory($ecotoneDbalSettinges);

                $containerBuilder->addDefinitions([
                    DbalConnectionFactory::class => $dbalConnection,
                ]);
            }
        }

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
        $containerBuilder->addDefinitions(__DIR__ . '/Config/DI/monolog.php');
        $containerBuilder->addDefinitions(__DIR__ . '/Config/DI/twig.php');
        $containerBuilder->addDefinitions(__DIR__ . '/Config/DI/controller.php');
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
        //        // 1 READ CONFIG VALUES FROM DEFAULT FILE
        //        if (! file_exists($file = Module::openemrDir() . DIRECTORY_SEPARATOR . 'sites/default/sqlconfig.php')) {
        //            $sqlconfig = require_once $file;
        //        }
        //
        //        // 2 PREPARE CUSTOM ARRAY FOR ECOTONE DBAL CONN.
        //        $ecotoneDbalSettinges = [
        //            'connection' => [
        //                'dbname' => $sqlconfig['dbase'],
        //                'user' => $sqlconfig['login'],
        //                'password' => $sqlconfig['pass'],
        //                'host' => $sqlconfig['host'],
        //                'driver' => $sqlconfig['mysql2'],
        //            ],
        //            'table_name' => 'oe_module_todo_list_enqueue',
        //        ];
        //        // 3 CREATE ECOTONE DBAL CONN.
        //
        //        $dbalConnection = new DbalConnectionFactory($ecotoneDbalSettinges);

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
                //DbalConnectionFactory::class => new DbalConnectionFactory(sprintf("sqlite:////%s%s%s", TodoListContext::getModuleDir(), '/var/module_data/', TodoListContext::getSqLiteDatabaseName())),
            ],
            configurationVariables: [],
            serviceConfiguration: $serviceConfiguration,
            cacheConfiguration: false,
            pathToRootCatalog: $rootCatalog . '/src',
            classesToRegister: []
        );
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        return $this->container->get($id);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $id Identifier of the entry to look for.
     */
    public function has($id): bool
    {
        return $this->container->has($id);
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
