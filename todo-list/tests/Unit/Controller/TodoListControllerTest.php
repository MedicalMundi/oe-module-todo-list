<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Tests\Unit\Controller;

use MedicalMundi\TodoList\Adapter\Http\Common\UrlService;
use MedicalMundi\TodoList\Adapter\Http\Web\ToDoListController;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\FindTodosPort;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Twig\Environment;

class TodoListControllerTest extends TestCase
{
    private const UUID = '945a0258-7751-478a-9d01-7d925963c740';

    private const INVALID_UUID = '945a0258-7751-478a-9d01-';

    private ToDoListController $controller;

    /**
     * @var FindTodosPort|MockObject
     */
    private MockObject $repository;

    /**
     * @var UrlService|MockObject
     */
    private MockObject $urlService;

    /**
     * @var Environment|MockObject
     */
    private MockObject $templateEngine;

    protected function setUp(): void
    {
        $this->repository = $this->getMockForAbstractClass(FindTodosPort::class);
        $this->urlService = $this->createMock(UrlService::class);
        $this->templateEngine = $this->createMock(Environment::class);
        $this->controller = new ToDoListController(
            $this->repository,
            $this->urlService,
            $this->templateEngine
        );
    }

    public function testSuccess(): void
    {
        $params = [];
        $request = new ServerRequest('GET', '/todos', [], null, '1.1', $params);

        $this->repository
            ->expects($this->once())
            ->method('findTodos')
        ;

        $this->templateEngine
            ->expects($this->once())
            ->method('render')
        ;

        $response = $this->controller->__invoke($request, $params);

        self::assertSame(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function canHandleInvalidUuidRequestParam(): void
    {
        self::markTestSkipped();
        $params = [
            'id' => self::INVALID_UUID,
        ];
        $request = new ServerRequest('GET', '/todos/', [], null, '1.1', $params);

        $this->expectException(InvalidUuidStringException::class);
        $this->expectExceptionMessage('Invalid UUID string: ' . self::INVALID_UUID);

        $this->controller->__invoke($request, $params);
    }
}
