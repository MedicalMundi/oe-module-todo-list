<?php declare(strict_types=1);

namespace OpenEMR\Modules\MedicalMundiTodoList\Tests\Unit\Controller;

use League\Route\Http\Exception\BadRequestException;
use MedicalMundi\TodoList\Application\Port\In\AddTodoUseCase;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\AddTodoPort;
use MedicalMundi\TodoList\Tests\Unit\Controller\JsonResponse;
use MedicalMundi\TodoList\Tests\Unit\Controller\ToDo;
use Nyholm\Psr7\ServerRequest;
use OpenEMR\Modules\MedicalMundiTodoList\Adapter\Http\Web\AddTodoController;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AddTodoControllerTest extends TestCase
{
    private AddTodoController $controller;

    /**
     * @var AddTodoPort|MockObject
     */
    private MockObject $repository;

    /**
     * @var AddTodoUseCase|MockObject
     */
    private MockObject $useCaseService;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(AddTodoPort::class);

        $this->useCaseService = $this->createMock(AddTodoUseCase::class);
        $this->controller = new AddTodoController($this->useCaseService);
    }

    public function testSuccess(): void
    {
        self::markTestIncomplete();
        $data = [
            'todo_id' => 'foo',
            'title' => 'irrelevant title',
            'body' => 'irrelevant body',
        ];
        $request = (new ServerRequest('GET', '/'))->withParsedBody($data);

        $this->useCaseService->expects($this->once())->method('addTodo');

        $response = $this->controller->__invoke($request, []);

        self::assertSame(201, $response->getStatusCode());
        self::assertInstanceOf(JsonResponse::class, $response); /** @var JsonResponse $response */
        self::assertInstanceOf(ToDo::class, $response->getPayload());
        /** @var ToDo $item */
        $item = $response->getPayload();
        self::assertSame($data['name'], $item->getName());
        self::assertSame($data['dueFor'], $item->getDueForAsString());
        self::assertSame($data['doneAt'], $item->getDoneAtAsString());
    }

    /**
     * @test
     */
    public function canHandleInvalidRequestBody(): void
    {
        self::markTestIncomplete();
        $request = new ServerRequest('GET', '/');
        $request->withoutAttribute('Content-Type');

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('Invalid request body');

        $this->controller->__invoke($request, []);
    }
}
