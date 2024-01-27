<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web;

use Ecotone\Modelling\CommandBus;
use MedicalMundi\TodoList\Application\Domain\Todo\Command\PostTodo;
use Nyholm\Psr7\Factory\Psr17Factory;
use Odan\Session\SessionInterface;
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
        private readonly SessionInterface $session,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() === 'POST' && null !== $request->getParsedBody()) {
            $form = $request->getParsedBody();

            $command = new PostTodo(Uuid::uuid4()->toString(), $form['title']);

            try {
                $result = $this->commandBus->send($command);
                $this->session->getFlash()->add('success', 'Todo saved!');

                //TODO: perform a redirect to todo details page
                return $this->render('todo/new.html.twig', []);
            } catch (\Exception $exception) {
                $this->session->getFlash()->add('error', 'Error: ' . $exception->getMessage());

                //TODO: populate twig form with sended data
                return $this->render('todo/new.html.twig', [
                    'form' => $form,
                ]);
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
