<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web;

use MedicalMundi\TodoList\Application\Port\Out\Persistence\LoadTodoPort;
use MedicalMundi\TodoList\Domain\Todo\TodoId;
use Nyholm\Psr7\Factory\Psr17Factory;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Common\UrlService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class ToDoReadController
{
    public function __construct(
        private LoadTodoPort $repository,
        private UrlService $urlService,
        private Environment $templateEngine
    ) {
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $todoId = TodoId::fromString((string) $args['id']);

        //TODO: return exception or 404 not found
        if (! ($todo = $this->repository->withTodoId($todoId))) {
            die('fix this in ToDoReadController ');
        };

        return $this->render('todo/show.html.twig', [
            'todo' => $todo,
        ]);
    }

    private function render(string $template, array $parameters): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $content = $this->templateEngine->render($template, $parameters);

        $responseBody = $psr17Factory->createStream($content);

        return $psr17Factory->createResponse(200)->withBody($responseBody);
    }
}
