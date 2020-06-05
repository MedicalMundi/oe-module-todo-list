<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Tests\Unit\Todo;

use MedicalMundi\TodoList\Domain\Todo\Title;
use MedicalMundi\TodoList\Domain\Todo\Todo;
use MedicalMundi\TodoList\Domain\Todo\TodoId;
use PHPUnit\Framework\TestCase;

class TodoTest extends TestCase
{
    private const UUID = '048a23d9-db59-4d49-87e0-36a05ee08593';

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function can_be_created(): void
    {
        new Todo(TodoId::generate(), Title::fromString('irrelevant'));

        new Todo(TodoId::fromString(self::UUID), Title::fromString('irrelevant'));
    }
}
