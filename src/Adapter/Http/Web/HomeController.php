<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web;

use Nyholm\Psr7\Factory\Psr17Factory;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Common\UrlService;
use OpenEMR\Modules\MedicalMundiTodoList\isModuleStandAlone;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class HomeController
{
    private UrlService $urlService;

    private Environment $templateEngine;

    public function __construct(UrlService $urlService, Environment $templateEngine)
    {
        $this->urlService = $urlService;
        $this->templateEngine = $templateEngine;
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $page = '<div><h1>Home controller !!<h1> standAlone: ' . (int) (new isModuleStandAlone())() . '</div>';
        $page .= '<div>request_uri: ' . $request->getUri() . '</div>';
        $page .= '<hr>';
        $page .= '<hr>';
        $page .= '<div>Menu</div>';
        $page .= '<div>Link test - <a href="' . $this->urlService->renderUrl('main') . '">home page</a></div>';
        $page .= '<div>Link test - <a href="' . $this->urlService->renderUrl('about') . '">about page</a></div>';
        $page .= '<div>Link test - <a href="' . $this->urlService->renderUrl('help') . '">help page</a></div>';

        $page .= '<hr>';
        $page .= '<hr>';
        $page .= '<div>Link test - <a href="' . $this->urlService->renderUrl('todo-list') . '">show todo list</a></div>';
        $page .= '<div>Link test - <a href="' . $request->getUri() . 'todos/23' . '">show todo by id 23</a></div>';

        return $this->render('home.html.twig', []);
    }

    private function render(string $template, array $parameters): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();

        $content = $this->templateEngine->render($template, $parameters);

        $responseBody = $psr17Factory->createStream($content);

        return $psr17Factory->createResponse(200)->withBody($responseBody);
    }
}
