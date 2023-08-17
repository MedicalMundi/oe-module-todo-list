<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Tests\Integration;

use MedicalMundi\TodoList\Application\AddTodoService;
use MedicalMundi\TodoList\Application\Port\In\AddTodoCommand;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\AddTodoPort;
use MedicalMundi\TodoList\Domain\Todo\Exception\CouldNotSaveTodo;
use MedicalMundi\TodoList\Domain\Todo\Title;
use MedicalMundi\TodoList\Domain\Todo\TodoId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class AddTodoServiceTest extends TestCase
{
    /**
     * @var AddTodoPort|MockObject
     */
    private ?MockObject $repository = null;

    /**
     * @var LoggerInterface|MockObject
     */
    private MockObject $logger;

    private ?AddTodoService $useCase = null;

    protected function setUp(): void
    {
        $this->repository = $this->getMockForAbstractClass(AddTodoPort::class);
        $this->logger = $this->getMockForAbstractClass(LoggerInterface::class);
        $this->useCase = new AddTodoService($this->repository, $this->logger);
    }

    /**
     * @test
     */
    public function should_add_a_new_todo(): void
    {
        $command = $this->getMockBuilder(AddTodoCommand::class)
            ->disableOriginalConstructor()
            ->getMock();

        $command->expects($this->once())
            ->method('todoId')
            ->willReturn(TodoId::generate());

        $command->expects($this->once())
            ->method('title')
            ->willReturn(Title::fromString('a title'));

        $this->repository->expects($this->once())
            ->method('addTodo')
        ;

        $this->useCase->addTodo($command);
    }

    /**
     * @test
     */
    public function should_log_an_exception(): void
    {
        $command = $this->getMockBuilder(AddTodoCommand::class)
            ->disableOriginalConstructor()
            ->getMock();

        $command->expects($this->once())
            ->method('todoId')
            ->willReturn(TodoId::generate());

        $command->expects($this->once())
            ->method('title')
            ->willReturn(Title::fromString('a title'));

        $this->repository->expects($this->once())
            ->method('addTodo')
            ->willThrowException(CouldNotSaveTodo::becauseDatabaseError())
        ;

        $this->logger->expects($this->once())
            ->method('error')
            ->with('Could not save todo, database error occur.')
        ;

        $this->useCase->addTodo($command);
    }

    protected function tearDown(): void
    {
        $this->useCase = null;
        $this->repository = null;
    }
}
