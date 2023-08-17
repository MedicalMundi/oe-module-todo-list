<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Tests\Unit\Todo;

use MedicalMundi\TodoList\Domain\Todo\Description;
use MedicalMundi\TodoList\Domain\Todo\Title;
use MedicalMundi\TodoList\Domain\Todo\Todo;
use MedicalMundi\TodoList\Domain\Todo\TodoId;
use PHPUnit\Framework\TestCase;

class TodoTest extends TestCase
{
    private const UUID = '048a23d9-db59-4d49-87e0-36a05ee08593';

    private const TITLE = 'A title';

    private const DESCRIPTION = 'A description';

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function can_be_created(): void
    {
        new Todo(TodoId::generate(), Title::fromString(self::TITLE));

        new Todo(TodoId::fromString(self::UUID), Title::fromString(self::TITLE));
    }

    /**
     * @test
     */
    public function should_return_the_identifier(): void
    {
        $todo = new Todo(TodoId::fromString(self::UUID), Title::fromString(self::TITLE));

        $id = $todo->id();

        self::assertEquals(self::UUID, $id->toString());
    }

    /**
     * @test
     */
    public function should_return_the_title(): void
    {
        $todo = new Todo(TodoId::fromString(self::UUID), Title::fromString(self::TITLE));

        $title = $todo->title();

        self::assertEquals(self::TITLE, $title->toString());
    }

    /**
     * @test
     */
    public function can_assign_a_new_title(): void
    {
        $todo = new Todo(TodoId::fromString(self::UUID), Title::fromString(self::TITLE));

        $todo->changetitle($newTitle = Title::fromString('A new title'));

        self::assertEquals($newTitle, $todo->title());
    }

    /**
     * @test
     */
    public function can_assign_a_new_description(): void
    {
        $todo = new Todo(TodoId::fromString(self::UUID), Title::fromString(self::TITLE));

        $todo->changeDescription($newDescription = Description::fromString('A new Description'));

        self::assertEquals($newDescription, $todo->description());
    }

    /**
     * @test
     */
    public function should_return_null_as_description(): void
    {
        $todo = new Todo(TodoId::fromString(self::UUID), Title::fromString(self::TITLE));

        $description = $todo->description();

        self::assertNull($description);
    }
}
