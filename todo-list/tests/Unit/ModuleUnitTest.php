<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Tests\Unit;

use League\Route\Http\Exception as HttpException;
use League\Route\Router;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use OpenEMR\Modules\MedicalMundiTodoList\Module;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ModuleUnitTest extends TestCase
{
    private Module $module;


    private MockObject|Router $router;

    protected function setUp(): void
    {
        $this->router = $this->createMock(Router::class);
        $this->module = new Module($this->router);
    }

    /**
     * @test
     */
    public function can_execute_static_bootstrap(): void
    {
        $this->expectNotToPerformAssertions();

        Module::bootstrap();
    }

    /**
     * @test
     */
    public function ItHandlesHttpAndDomainExceptions(): void
    {
        self::markTestIncomplete();
        $exception = new HttpException(401, 'foo');
        $request = new ServerRequest('GET', '/foo');

        $this->router
            ->method('dispatch')
            ->willThrowException($exception)
        ;

        $response = $this->module->handle($request);
        $expectedStatusCode = $exception instanceof HttpException ? $exception->getStatusCode() : 400;
        self::assertSame($expectedStatusCode, $response->getStatusCode());
        self::assertInstanceOf(Response::class, $response);
        $payload = $response->getBody()->getContents();
        self::assertIsString($payload);
    }
}
