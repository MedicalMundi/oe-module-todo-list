<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Adapter\Http\Web;

use MedicalMundi\TodoList\Adapter\Http\Common\UrlService;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ToDoReadController
{
    /** @var UrlService */
    private $urlService;

    /**
     * TodoListController constructor.
     * @param UrlService $urlService
     */
    public function __construct(UrlService $urlService)
    {
        $this->urlService = $urlService;
    }

    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $page = '<div><h1>ToDoRead Controller !!</h1></div>';
        $page .= '<div>request_uri: '.$request->getUri().'</div>';
        $page .= '<div>arguments count: '.count($args).'</div>';
        $page .= '<div>arguments id: '.$args['id'].'</div>';
        $page .= '<hr>';
        $page .= '<hr>';
        $page .= '<div>Menu</div>';
        $page .= '<div>Link test - <a href="'.$this->urlService->renderUrl('main').'">home page</a></div>';
        $page .= '<div>Link test - <a href="'.$this->urlService->renderUrl('about').'">about page</a></div>';
        $page .= '<div>Link test - <a href="'.$this->urlService->renderUrl('help').'">help page</a></div>';
        $page .= '<hr>';
        $page .= '<hr>';
        $page .= '<div>Link test - <a href="'.$this->urlService->renderUrl('todo-list').'">show todo list</a></div>';
        $page .= '<div>Link test - <a href="'.$request->getUri().'/23'.'">show todo by id 23</a></div>';
        $page .= '<hr>';

        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
        $responseBody = $psr17Factory->createStream($page);

        return $psr17Factory->createResponse(200)->withBody($responseBody);
    }
}
