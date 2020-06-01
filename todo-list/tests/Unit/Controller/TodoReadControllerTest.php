<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Tests\Unit\Controller;

use MedicalMundi\TodoList\Adapter\Http\Common\UrlService;
use MedicalMundi\TodoList\Adapter\Http\Web\ToDoReadController;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\LoadTodoPort;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Twig\Environment;

class TodoReadControllerTest extends TestCase
{
    private const UUID = '945a0258-7751-478a-9d01-7d925963c740';
    private const INVALID_UUID = '945a0258-7751-478a-9d01-';

    /** @var TodoReadController */
    private $controller;

    /** @var LoadTodoPort|MockObject */
    private $repository;

    /** @var UrlService|MockObject */
    private $urlService;


    protected function setUp(): void
    {
        $this->repository = $this->getMockForAbstractClass(LoadTodoPort::class);
        $this->urlService = $this->createMock(UrlService::class);
        $this->controller = new TodoReadController(
            $this->repository,
            $this->urlService,
            $this->createMock(Environment::class)
        );
    }

    public function testSuccess(): void
    {
        self::markTestIncomplete('Remove final from TodoId class.');
        $params = ['id' => self::UUID];
        $request = new ServerRequest('GET', '/todos/', [], null, '1.1', $params);

        $this->repository
            ->expects($this->once())
            ->method('withTodoId')
            //->with(TodoId::fromString($uuid))
        ;

        $response = $this->controller->__invoke($request, $params);

        self::assertSame(200, $response->getStatusCode());
    }

    /** @test */
    public function canHandleInvalidUuidRequestParam(): void
    {
        $params = ['id' => self::INVALID_UUID];
        $request = new ServerRequest('GET', '/todos/', [], null, '1.1', $params);

        $this->expectException(InvalidUuidStringException::class);
        $this->expectExceptionMessage('Invalid UUID string: ' . self::INVALID_UUID);

        $this->controller->__invoke($request, $params);
    }
}
