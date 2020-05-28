<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Tests;

use League\Route\Http\Exception as HttpException;
use League\Route\Router;
use MedicalMundi\TodoList\Adapter\Http\Common\UrlService;
use MedicalMundi\TodoList\Adapter\Persistence\InMemory\InMemoryTodoRepository;
use MedicalMundi\TodoList\Module;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class ModuleUnitTest extends TestCase
{
    /** @var Module */
    private $module;

    /** @var Router & MockObject */
    private $router;

    protected function setUp(): void
    {
        $this->router = $this->createMock(Router::class);
        $this->module = new Module($this->router);
    }

    /** @test */
    public function can_execute_static_bootstrap(): void
    {
        $this->expectNotToPerformAssertions();

        Module::bootstrap();
    }

    /** @test */
    public function ItHandlesHttpAndDomainExceptions(): void //(Exception $exception)
    {
        self::markTestIncomplete();
        $exception = new HttpException(401, 'foo');
        $request = new ServerRequest('GET', '/foo');

        $this->router
            ->method('dispatch')
            ->willThrowException($exception)
        ;

        $response = $this->module->handle($request);
        $expectedStatusCode = $exception instanceof HttpException ? $exception->getStatusCode(): 400;
        self::assertSame($expectedStatusCode, $response->getStatusCode());
        self::assertInstanceOf(Response::class, $response);
        $payload = $response->getBody()->getContents();
        self::assertIsString($payload);
    }

    /** @test */
    public function has_a_router(): void
    {
        $module = Module::bootstrap();

        self::assertTrue($module->has('router'));
    }

    /** @test */
    public function has_a_urlService(): void
    {
        $module = Module::bootstrap();

        self::assertInstanceOf(UrlService::class, $module->get('MedicalMundi\TodoList\Adapter\Http\Common\UrlService'));
    }

    /** @test */
    public function has_a_inMemoryRepository(): void
    {
        $module = Module::bootstrap();

        self::assertInstanceOf(InMemoryTodoRepository::class, $module->get('MedicalMundi\TodoList\Adapter\Persistence\InMemory\InMemoryTodoRepository'));
    }
}
