<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\WebApi\Todo;

use Ecotone\Messaging\Store\Document\DocumentStore;
use Exception;
use MedicalMundi\TodoList\Application\Domain\Todo\Todo;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class WapiShowTodoController
{
    public function __construct(
        private DocumentStore $documentStore,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $todoId = $request->getAttribute('id');

        try {
            $todo = $this->documentStore->findDocument('aggregates_' . Todo::class, $todoId);

            $jsonData = json_encode($todo, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            $this->logger->debug($e->getMessage());
            //TODO return 500 error
        }

        return $this->jsonResponse($jsonData);
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
