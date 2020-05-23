<?php declare(strict_types=1);


namespace MedicalMundi\TodoList;

use HttpException;
use League\Route\Router;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
        $router = (new RouterFactory)($containerBuilder);
        $containerBuilder->set('router', $router);

        $module = new self();
        $containerBuilder->set('module', $module);
        $module->container = $containerBuilder->compile();
        $module->router = $router;

        return $module;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
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
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
        try {
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
