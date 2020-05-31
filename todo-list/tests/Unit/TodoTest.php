<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Tests\Unit;

use MedicalMundi\TodoList\Domain\Todo\Title;
use MedicalMundi\TodoList\Domain\Todo\Todo;
use MedicalMundi\TodoList\Domain\Todo\TodoId;
use PHPUnit\Framework\TestCase;

class TodoTest extends TestCase
{
    /** @test */
    public function can_be_created(): void
    {
        $todo = new Todo(TodoId::generate(), Title::fromString('irrelevant'));

        self::assertInstanceOf(Todo::class, $todo);
    }
}
