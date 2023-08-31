<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\WebApi\Todo;

use Ecotone\Messaging\Store\Document\DocumentStore;
use MedicalMundi\TodoList\Application\Domain\Todo\Todo;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class WapiToDoListController
{
    public function __construct(
        private DocumentStore $documentStore,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        try {
            $todos = $this->documentStore->getAllDocuments('aggregates_' . Todo::class);
            $jsonData = json_encode($todos, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
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
