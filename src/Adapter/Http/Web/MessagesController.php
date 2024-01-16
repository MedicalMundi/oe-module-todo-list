<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web;

use Ecotone\Messaging\Store\Document\DocumentStore;
use Ecotone\Modelling\CommandBus;
use Ecotone\Modelling\QueryBus;
use MedicalMundi\TodoList\Application\Domain\Setting\InitializeModuleSetting;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class MessagesController
{
    public function __construct(
        private readonly Environment $templateEngine,
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
        private readonly DocumentStore $documentStore,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $this->commandBus->send(
            new InitializeModuleSetting(1)
        );

        $moduleSettings = $this->documentStore->getDocument('aggregates_MedicalMundi\TodoList\Domain\Setting\ModuleSetting', '1');

        return $this->render('messages.html.twig', [
            'module_settings' => $moduleSettings,
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
