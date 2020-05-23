<?php declare(strict_types=1);


namespace MedicalMundi\TodoList;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ToDoListController
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

        $responseBody = $psr17Factory->createStream('ToDoList controller !!');

        return $psr17Factory->createResponse(200)->withBody($responseBody);
    }
}
