<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Tests\Functional\PersistenceAdapter;

use MedicalMundi\TodoList\Adapter\Persistence\FileSystem\JsonTodoRepository;
use MedicalMundi\TodoList\Domain\Todo\Description;
use MedicalMundi\TodoList\Domain\Todo\Exception\CouldNotRetrieveTodo;
use MedicalMundi\TodoList\Domain\Todo\Exception\CouldNotSaveTodo;
use MedicalMundi\TodoList\Domain\Todo\Title;
use MedicalMundi\TodoList\Domain\Todo\Todo;
use MedicalMundi\TodoList\Domain\Todo\TodoId;
use PHPUnit\Framework\TestCase;

class JsonTodoRepositoryTest extends TestCase
{
    private ?JsonTodoRepository $repository = null;

    protected function setUp(): void
    {
        $this->repository = new JsonTodoRepository();
    }

    /**
     * @test
     */
    public function should_add_a_todo(): void
    {
        $todo = $this->generateFixtureTodo();

        $this->repository->addTodo($todo);

        self::assertInstanceOf(Todo::class, $this->repository->withTodoId($todo->id()));
    }

    /**
     * @test
     */
    public function adding_duplicated_todo_should_throw_exception(): void
    {
        $uuid = '048a23d9-db59-4d49-87e0-36a05ee08593';
        $this->expectException(CouldNotSaveTodo::class);
        $this->expectExceptionMessage(\sprintf('Could not save todo with Id %s, duplicated todoId detected.', $uuid));

        $todo = $this->generateFixtureTodo($uuid);
        $this->repository->addTodo($todo);

        $this->repository->addTodo($todo);
    }

    /**
     * @test
     */
    public function should_retrieve_a_todo_by_identifier(): void
    {
        $todo = $this->generateFixtureTodo();
        $this->repository->addTodo($todo);

        $todoFromDatabase = $this->repository->withTodoId($todo->id());

        self::assertTrue($todo->id()->equals($todoFromDatabase->id()));
        self::assertTrue($todo->title()->equals($todoFromDatabase->title()));
    }

    /**
     * @test
     */
    public function loading_todo_for_update_should_throw_exception_if_a_todo_doesnt_exist(): void
    {
        $todoId = TodoId::fromString($uuid = '048a23d9-db59-4d49-87e0-36a05ee08593');
        $this->expectException(CouldNotRetrieveTodo::class);
        $this->expectExceptionMessage(\sprintf('Todo with Id %s does not exist.', $uuid));

        $this->repository->withTodoId($todoId);
    }

    /**
     * @test
     */
    public function should_find_all_todos(): void
    {
        $this->repository->addTodo($this->generateFixtureTodo());
        $this->repository->addTodo($this->generateFixtureTodo());
        $this->repository->addTodo($this->generateFixtureTodo());

        $result = $this->repository->findTodos();

        self::assertNotEmpty($result);
        self::assertIsArray($result);
        self::assertEquals(3, count($result));
    }

    private function generateFixtureTodo(string $uuid = null): Todo
    {
        $todoId = $uuid ? TodoId::fromString($uuid) : TodoId::generate();
        $title = Title::fromString('a todo title');
        $description = Description::fromString('a todo description');

        return new Todo($todoId, $title);
    }

    protected function tearDown(): void
    {
        $this->repository = null;
    }
}
