<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web;

use Ecotone\Messaging\Store\Document\DocumentStore;
use MedicalMundi\TodoList\Application\Domain\Todo\Todo;
use MedicalMundi\TodoList\Application\Domain\Todo\TodoBusinessRepositoryInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Common\UrlService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class ToDoListController
{
    public function __construct(
        private UrlService $urlService,
        private Environment $templateEngine,
        private DocumentStore $documentStore,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $todos = $this->documentStore->getAllDocuments('aggregates_' . Todo::class);

        return $this->render('todo/list.html.twig', [
            'todos' => $todos,
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
