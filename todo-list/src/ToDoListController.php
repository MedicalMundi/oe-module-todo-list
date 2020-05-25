<?php declare(strict_types=1);


namespace MedicalMundi\TodoList;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ToDoListController
{
    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $page = '<div>ToDoList Controller !!</div>';
        $page .= '<div>request_uri: '.$request->getUri().'</div>';
        $page .= '<div>arguments count: '.count($args).'</div>';
        $page .= '<hr>';
        $page .= '<hr>';
        $page .= '<div>Menu</div>';
        $page .= '<div>Link test - <a href="/">home page</a></div>';
        $page .= '<div>Link test - <a href="/about">about page</a></div>';
        $page .= '<div>Link test - <a href="/help">help page</a></div>';
        $page .= '<hr>';
        $page .= '<hr>';
        $page .= '<div>Link test - <a href="'.$request->getUri().'todos'.'">show todo list</a></div>';
        $page .= '<div>Link test - <a href="'.$request->getUri().'/23'.'">show todo by id 23</a></div>';
        $page .= '<hr>';

        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

        $responseBody = $psr17Factory->createStream($page);

        return $psr17Factory->createResponse(200)->withBody($responseBody);
    }
}
