<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Tests\Unit;

use MedicalMundi\TodoList\Application\Port\In\AddTodoCommand;
use MedicalMundi\TodoList\Domain\Todo\Description;
use MedicalMundi\TodoList\Domain\Todo\Title;
use MedicalMundi\TodoList\Domain\Todo\TodoId;
use PHPUnit\Framework\TestCase;

class AddTodoCommandTest extends TestCase
{
    /** @test */
    public function can_be_created(): void
    {
        $command = new AddTodoCommand(TodoId::generate(), Title::fromString('irrelevant'));

        self::assertInstanceOf(TodoId::class, $command->todoId());
        self::assertInstanceOf(Title::class, $command->title());
    }

    /** @test */
    public function can_be_created_with_description(): void
    {
        $command = new AddTodoCommand(TodoId::generate(), Title::fromString('irrelevant'), Description::fromString('irrelevant'));

        self::assertInstanceOf(TodoId::class, $command->todoId());
        self::assertInstanceOf(Title::class, $command->title());
        self::assertInstanceOf(Description::class, $command->description());
    }
}
