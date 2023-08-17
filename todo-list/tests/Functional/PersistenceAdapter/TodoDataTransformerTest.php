<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Tests\Functional\PersistenceAdapter;

use MedicalMundi\TodoList\Adapter\Persistence\FileSystem\TodoDataTransformer;
use MedicalMundi\TodoList\Domain\Todo\Title;
use MedicalMundi\TodoList\Domain\Todo\Todo;
use MedicalMundi\TodoList\Domain\Todo\TodoId;
use PHPUnit\Framework\TestCase;

class TodoDataTransformerTest extends TestCase
{
    private const UUID = '945a0258-7751-478a-9d01-7d925963c740';

    private const TITLE = 'irrelevant title';

    private ?TodoDataTransformer $transformer = null;

    protected function setUp(): void
    {
        $this->transformer = new TodoDataTransformer();
    }

    /**
     * @test
     */
    public function should_transform_TodoInput_in_to_array(): void
    {
        $todoInput = new Todo(
            TodoId::fromString(self::UUID),
            Title::fromString(self::TITLE)
        );

        $result = $this->transformer->transformFromTodo($todoInput);

        self::assertIsArray($result);
        self::assertArrayHasKey('id', $result);
        self::assertEquals(self::UUID, $result['id']);
        self::assertArrayHasKey('title', $result);
        self::assertEquals(self::TITLE, $result['title']);
    }

    /**
     * @test
     */
    public function should_transform_arrayInput_to_Todo_object(): void
    {
        $input = [
            self::UUID => [
                'id' => self::UUID,
                'title' => self::TITLE,
            ],
        ];

        $result = $this->transformer->transformFromArray($input);

        self::assertInstanceOf(Todo::class, $result);
        self::assertEquals(self::UUID, $result->id()->toString());
        self::assertEquals(self::TITLE, $result->title()->toString());
    }

    /**
     * @test
     */
    public function should_throw_transform_exception(): void
    {
        self::markTestIncomplete('Implement..');
    }

    protected function tearDown(): void
    {
        $this->transformer = null;
    }
}
