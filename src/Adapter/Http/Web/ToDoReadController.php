<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web;

use Ecotone\Messaging\Store\Document\DocumentStore;
use MedicalMundi\TodoList\Application\Domain\Todo\Todo;
use Nyholm\Psr7\Factory\Psr17Factory;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Common\UrlService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class ToDoReadController
{
    public function __construct(
        private UrlService $urlService,
        private Environment $templateEngine,
        private DocumentStore $documentStore,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $todo = $this->documentStore->getDocument('aggregates_' . Todo::class, (string) $args['id']);

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
