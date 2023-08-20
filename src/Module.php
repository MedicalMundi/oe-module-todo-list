<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList;

use Ecotone\Lite\EcotoneLite;
use Ecotone\Messaging\Config\ConfiguredMessagingSystem;
use Ecotone\Messaging\Config\ServiceConfiguration;
use League\Route\Http\Exception as HttpException;
use League\Route\Router;
use Nyholm\Psr7\Factory\Psr17Factory;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Common\RouterFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Module implements ContainerInterface, RequestHandlerInterface
{
    public const MODULE_NAME = 'ToDo List';

    public const MODULE_VERSION = '@dev';

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

    public static function bootstrap(): self
    {
        $module = new self();
        $container = $module->buildContainer();
        $module->bootstrapEcotone($container);
        $router = (new RouterFactory())($container);
        $module->container = $container;
        $module->router = $router;

        return $module;
    }

    private function buildContainer(): ContainerInterface
    {
        $containerBuilder = new \DI\ContainerBuilder();
        $containerBuilder->useAutowiring(true);
        $containerBuilder->useAttributes(true);
        $containerBuilder->addDefinitions(__DIR__ . '/Config/DI/monolog.php');
        $containerBuilder->addDefinitions(__DIR__ . '/Config/DI/twig.php');
        $containerBuilder->addDefinitions(__DIR__ . '/Config/DI/controller.php');

        return $containerBuilder->build();
    }

    private static function bootstrapEcotone(ContainerInterface $container): ConfiguredMessagingSystem
    {
        $rootCatalog = realpath(__DIR__ . '/..');

        $serviceConfiguration = ServiceConfiguration::createWithDefaults()
            ->withLoadCatalog($rootCatalog . '/src')
            ->withNamespaces(['MedicalMundi']);

        return EcotoneLite::bootstrap(
            [],  //array $classesToResolve
            $container, //containerOrAvailableServices
            $serviceConfiguration,
            [],  //array $configurationVariables
            $cacheConfiguration = false,
            $pathToRootCatalog = $rootCatalog . '/src',
            $allowGatewaysToBeRegisteredInContainer = true
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
}
