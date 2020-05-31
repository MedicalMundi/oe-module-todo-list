<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Tests\Unit;

use MedicalMundi\TodoList\Domain\Todo\Exception\CouldNotSaveTodo;
use MedicalMundi\TodoList\Domain\Todo\TodoId;
use PHPUnit\Framework\TestCase;

class CouldNotSaveTodoTest extends TestCase
{
    private const UUID = '048a23d9-db59-4d49-87e0-36a05ee08593';

    /** @test */
    public function when_todo_doest_exist_should_return_a_correct_error_message(): void
    {
        $this->expectException(CouldNotSaveTodo::class);
        $this->expectExceptionMessage(\sprintf('Could not save todo with Id %s does not exist.', self::UUID));

        throw CouldNotSaveTodo::becauseTodoNotExist(TodoId::fromString(self::UUID)->toString());
    }

    /** @test */
    public function when_a_duplicated_todo_id_is_detected_should_return_a_correct_error_message(): void
    {
        $this->expectException(CouldNotSaveTodo::class);
        $this->expectExceptionMessage(\sprintf('Could not save todo with Id %s, duplicated todoId detected.', self::UUID));

        throw CouldNotSaveTodo::becauseDuplicateTodoIdDetected(TodoId::fromString(self::UUID)->toString());
    }

    /** @test */
    public function when_database_connection_is_down_should_return_a_correct_error_message(): void
    {
        $this->expectException(CouldNotSaveTodo::class);
        $this->expectExceptionMessage('Could not save todo, database connection is down.');

        throw CouldNotSaveTodo::becauseDatabaseConnection();
    }

    /** @test */
    public function when_unknow_database_error_occour_should_return_a_generic_error_message(): void
    {
        $this->expectException(CouldNotSaveTodo::class);
        $this->expectExceptionMessage('Could not save todo, database error occur.');

        throw CouldNotSaveTodo::becauseDatabaseError();
    }
}
