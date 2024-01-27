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
        private readonly CommandBus $commandBus,
        private readonly Environment $templateEngine,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() === 'POST' && null !== $request->getParsedBody()) {
            $form = $request->getParsedBody();

            $command = new PostTodo(Uuid::uuid4()->toString(), $form['title']);

            $result = $this->commandBus->send($command);

            if (Uuid::isValid($result)) {
                // TODO: add session + flash message feature
                //$this->session->getFlash()->add('success', 'Todo saved!');

                //TODO: perform a redirect to todo details page
            } else {
                // TODO: add session + flash message feature
                //$this->session->getFlash()->add('error', 'Error');

                // TODO: show error and form with data
            }

            return $this->render('todo/new.html.twig', []);
        }

        return $this->render('todo/new.html.twig', []);
    }

    private function render(string $template, array $parameters): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $content = $this->templateEngine->render($template, $parameters);

        $responseBody = $psr17Factory->createStream($content);

        return $psr17Factory->createResponse(200)->withBody($responseBody);
    }
}
