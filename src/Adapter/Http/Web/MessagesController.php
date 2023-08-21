<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web;

use Ecotone\Modelling\CommandBus;
use Ecotone\Modelling\QueryBus;
use MedicalMundi\TodoList\Application\Domain\Product\GetProductPriceQuery;
use MedicalMundi\TodoList\Application\Domain\Product\RegisterProductCommand;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class MessagesController
{
    public function __construct(
        private Environment $templateEngine,
        private CommandBus $commandBus,
        private QueryBus $queryBus,
    ) {
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $this->commandBus->send(new RegisterProductCommand(1, 100));

        $price = (int) $this->queryBus->send(new GetProductPriceQuery(1));

        return $this->render('messages.html.twig', [
            'productId' => 1,
            'price' => $price,
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
