<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web;

use MedicalMundi\TodoList\Application\Port\In\AddTodoCommand;
use MedicalMundi\TodoList\Application\Port\In\AddTodoUseCase;
use MedicalMundi\TodoList\Domain\Todo\Title;
use MedicalMundi\TodoList\Domain\Todo\TodoId;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class AddTodoController
{
    public function __construct(
        private AddTodoUseCase $useCaseService,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        //TODO add data validation
        if (! isset($args['id']) || ! isset($args['title'])) {
            $page = 'Validation error: ';
            $this->logger->log('info', 'Validation error: ');
            return $this->renderRaw($page);
        }

        $command = new AddTodoCommand(
            TodoId::fromString((string) $args['id']),
            Title::fromString((string) $args['title'])
        );

        $this->useCaseService->addTodo($command);

        $page = 'Success ';
        return $this->renderRaw($page);
    }

    private function renderRaw(string $content): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();
        $responseBody = $psr17Factory->createStream($content);

        return $psr17Factory->createResponse(200)->withBody($responseBody);
    }
}
