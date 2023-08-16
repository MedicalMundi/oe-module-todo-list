<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Tests\Unit;

use MedicalMundi\TodoList\Domain\Todo\Exception\CouldNotRetrieveTodo;
use PHPUnit\Framework\TestCase;

class CouldNotRetrieveTodoTest extends TestCase
{
    private const UUID = '048a23d9-db59-4d49-87e0-36a05ee08593';

    /**
     * @test
     */
    public function when_database_connection_is_down_should_return_a_correct_error_message(): void
    {
        $this->expectException(CouldNotRetrieveTodo::class);
        $this->expectExceptionMessage('Could not retrieve todo, database connection is down.');

        throw CouldNotRetrieveTodo::becauseDatabaseConnection();
    }

    /**
     * @test
     */
    public function when_unknow_database_error_occour_should_return_a_generic_error_message(): void
    {
        $this->expectException(CouldNotRetrieveTodo::class);
        $this->expectExceptionMessage('Could not retrieve todo, database error occur.');

        throw CouldNotRetrieveTodo::becauseDatabaseError();
    }
}
