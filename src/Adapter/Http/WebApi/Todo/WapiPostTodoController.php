<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\WebApi\Todo;

use Ecotone\Modelling\CommandBus;
use MedicalMundi\TodoList\Application\Domain\Todo\Command\PostTodo;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class WapiPostTodoController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        //TODO add data validation

        /** @var PostTodo $command */
        $command = $this->createCommandFromRequest((array) $request->getParsedBody());

        try {
            $todoId = $this->commandBus->send($command);
        } catch (\Exception $exception) {
            $this->logger->debug($exception->getMessage());
        }

        return $this->jsonResponse(json_encode([
            'id' => $todoId,
        ], JSON_THROW_ON_ERROR), 201);
    }

    private function createCommandFromRequest(array $requestParams): PostTodo
    {
        return new PostTodo((string) $requestParams['todoId'], (string) $requestParams['title']);
    }

    private function jsonResponse(string $jsonData, int $responseStatusCode = 200): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $responseBody = $psr17Factory->createStream($jsonData);

        return $psr17Factory->createResponse($responseStatusCode)
            ->withAddedHeader('Content-type', 'application/json')
            ->withBody($responseBody);
    }
}
