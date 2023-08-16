<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Adapter\Http\Web;

use MedicalMundi\TodoList\Adapter\Http\Common\UrlService;

use MedicalMundi\TodoList\Application\Port\Out\Persistence\FindTodosPort;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class ToDoListController
{
    /**
     * @var FindTodosPort
     */
    private $repository;

    /**
     * @var UrlService
     */
    private $urlService;

    /**
     * @var Environment
     */
    private $templateEngine;

    public function __construct(FindTodosPort $repository, UrlService $urlService, Environment $templateEngine)
    {
        $this->repository = $repository;
        $this->urlService = $urlService;
        $this->templateEngine = $templateEngine;
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $todos = $this->repository->findTodos();

        $content = $this->templateEngine->render('todo/list.html.twig', [
            'todos' => $todos,
        ]);

        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

        $responseBody = $psr17Factory->createStream($content);

        return $psr17Factory->createResponse(200)->withBody($responseBody);
    }
}
