<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web;

use MedicalMundi\TodoList\Application\Port\In\AddTodoCommand;
use MedicalMundi\TodoList\Application\Port\In\AddTodoUseCase;
use MedicalMundi\TodoList\Domain\Todo\Title;
use MedicalMundi\TodoList\Domain\Todo\TodoId;
use Nyholm\Psr7\Factory\Psr17Factory;
use OpenEMR\Modules\MedicalMundiTodoList\isModuleStandAlone;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AddTodoController
{
    private AddTodoUseCase $useCaseService;

    public function __construct(AddTodoUseCase $useCaseService)
    {
        $this->useCaseService = $useCaseService;
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $page = '<div><h1>AddTodo controller !!<h1> standAlone: ' . (int) (new isModuleStandAlone())() . '</div>';

        $command = new AddTodoCommand(
            TodoId::fromString((string) $args['id']),
            Title::fromString((string) $args['title'])
        );

        $this->useCaseService->addTodo($command);

        return $this->renderRaw($page);
    }

    private function renderRaw(string $content): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();
        $responseBody = $psr17Factory->createStream($content);

        return $psr17Factory->createResponse(200)->withBody($responseBody);
    }
}
