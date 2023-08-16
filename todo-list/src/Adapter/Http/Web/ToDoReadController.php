<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Adapter\Http\Web;

use MedicalMundi\TodoList\Adapter\Http\Common\UrlService;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\LoadTodoPort;
use MedicalMundi\TodoList\Domain\Todo\TodoId;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class ToDoReadController
{
    /** @var LoadTodoPort */
    private $repository;

    /** @var UrlService */
    private $urlService;

    /** @var Environment */
    private $templateEngine;

    /**
     * TodoListController constructor.
     * @param LoadTodoPort $repository
     * @param UrlService $urlService
     */
    public function __construct(LoadTodoPort $repository, UrlService $urlService, Environment $templateEngine)
    {
        $this->repository = $repository;
        $this->urlService = $urlService;
        $this->templateEngine = $templateEngine;
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $todoId = TodoId::fromString((string)$args['id']);
        if (!$todo = $this->repository->withTodoId($todoId)) {
            die('fix this in ToDoReadController ');
        };

        $content = $this->templateEngine->render('todo/show.html.twig', [
            'todo' => $todo,
        ]);
        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
        $responseBody = $psr17Factory->createStream($content);

        return $psr17Factory->createResponse(200)->withBody($responseBody);
    }
}
