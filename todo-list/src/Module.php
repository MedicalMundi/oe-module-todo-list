<?php declare(strict_types=1);


namespace MedicalMundi\TodoList;

use League\Route\Http\Exception as HttpException;
use League\Route\Router;
use MedicalMundi\TodoList\Adapter\Http\Common\RouterFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class Module implements ContainerInterface, RequestHandlerInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /** @var Router|null */
    private $router;

    public function __construct(?Router $router = null)
    {
        $this->router = $router ?: $router;
    }

    public static function bootstrap(): self
    {
        $containerBuilder = new ContainerBuilder();

        $loader = new PhpFileLoader($containerBuilder, new FileLocator(__DIR__.'/Config'));

        $router = (new RouterFactory)($containerBuilder);
        $containerBuilder->set('router', $router);

        $module = new self();
        $containerBuilder->set('module', $module);

        //TODO refactoring the container initialization
        //$containerBuilder->set('module', $this); write private function buildContainer(): ContainerInterface


        $loader->load('container-config.php');
        //dump($containerBuilder);
        $containerBuilder->compile(); //dump($containerBuilder);


        $module->container = $containerBuilder;
        $module->router = $router;

        return $module;
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
     *
     * @return bool
     */
    public function has($id): bool
    {
        return $this->container->has($id);
    }

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     *
     * @psalm-suppress MixedInferredReturnType
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

        try {
            /** @psalm-suppress PossiblyNullReference */
            $response = $this->router->dispatch($request);
            //} catch (InvalidDataException $e) {
            //return new JsonResponse([ 'error' => exception_to_array($e) ], 400);
        } catch (HttpException $e) {
            $responseBody = $psr17Factory->createStream(json_encode([ 'error' => $e->getMessage() ]));
            return $response = $psr17Factory->createResponse($e->getStatusCode())->withBody($responseBody);
        }

        return $response;
    }
}
