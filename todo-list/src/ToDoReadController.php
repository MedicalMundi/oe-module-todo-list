<?php declare(strict_types=1);


namespace MedicalMundi\TodoList;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ToDoReadController
{
    public function __invoke(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $page = '<div>ToDoRead Controller !!</div>';
        $page .= '<div>request_uri: '.$request->getUri().'</div>';
        $page .= '<div>arguments id: '.$args['id'].'</div>';

        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
        $responseBody = $psr17Factory->createStream($page);

        return $psr17Factory->createResponse(200)->withBody($responseBody);
    }
}
