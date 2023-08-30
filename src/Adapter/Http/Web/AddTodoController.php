<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web;

use Ecotone\Modelling\CommandBus;
use MedicalMundi\TodoList\Application\Domain\Todo\Command\PostTodo;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Twig\Environment;

class AddTodoController
{
    public function __construct(
        private CommandBus $commandBus,
        private Environment $templateEngine,
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if ('POST' === $request->getMethod()) {
            //TODO add data validation

            /** @var PostTodo $command */
            $command = $this->createCommandFromRequest((array) $request->getParsedBody());

            $todoId = $this->commandBus->send($command);

            $page = 'Success ' . $todoId;

            return $this->render('todo/new.html.twig', []);
        } elseif ('GET' === $request->getMethod()) {
            return $this->render('todo/new.html.twig', []);
        }
    }

    private function createCommandFromRequest(array $requestParams): PostTodo
    {
        // Uuid::uuid4()->toString(),
        return new PostTodo((string) $requestParams['todoId'], (string) $requestParams['title']);
    }

    private function render(string $template, array $parameters): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $content = $this->templateEngine->render($template, $parameters);

        $responseBody = $psr17Factory->createStream($content);

        return $psr17Factory->createResponse(200)->withBody($responseBody);
    }
}
