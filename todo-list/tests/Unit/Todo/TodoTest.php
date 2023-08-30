<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Tests\Unit\Todo;

use Ecotone\Lite\EcotoneLite;
use MedicalMundi\TodoList\Application\Domain\Todo\Command\ChangeTitle;
use MedicalMundi\TodoList\Application\Domain\Todo\Command\PostTodo;
use MedicalMundi\TodoList\Application\Domain\Todo\Title;
use MedicalMundi\TodoList\Application\Domain\Todo\Todo;
use MedicalMundi\TodoList\Application\Domain\Todo\TodoId;
use MedicalMundi\TodoList\Application\Domain\Todo\TodoStatus;
use PHPUnit\Framework\TestCase;

class TodoTest extends TestCase
{
    private const UUID = '048a23d9-db59-4d49-87e0-36a05ee08593';

    private const TITLE = 'A title';

    private const DESCRIPTION = 'A description';

    /**
     * @test
     */
    public function should_post_a_todo(): void
    {
        $expectedTodoId = TodoId::fromString(self::UUID);

        $expectedTitle = Title::fromString(self::TITLE);

        $expectedStatus = TodoStatus::OPEN();

        /** @var Todo $sut */
        $sut = EcotoneLite::bootstrapFlowTesting([Todo::class])
            ->sendCommand(new PostTodo(
                self::UUID,
                self::TITLE,
            ))
            ->getAggregate(Todo::class, $expectedTodoId);

        self::assertEquals($expectedTodoId, $sut->id());
        self::assertEquals($expectedTitle, $sut->title());
        self::assertNull($sut->description());
        self::assertEquals($expectedStatus, $sut->status());
    }

    /**
     * @test
     */
    public function should_return_the_identifier(): void
    {
        $expectedTodoId = TodoId::fromString(self::UUID);

        /** @var TodoId $todoIdFromAggregate */
        $todoIdFromAggregate = EcotoneLite::bootstrapFlowTesting([Todo::class])
            ->sendCommand(new PostTodo(
                self::UUID,
                self::TITLE,
            ))
            ->getAggregate(Todo::class, $expectedTodoId)
            ->id();

        self::assertEquals($expectedTodoId, $todoIdFromAggregate);
    }

    /**
     * @test
     */
    public function should_return_the_title(): void
    {
        $identifier = TodoId::fromString(self::UUID);

        $expectedTitle = Title::fromString(self::TITLE);

        /** @var Title $titleFromAggregate */
        $titleFromAggregate = EcotoneLite::bootstrapFlowTesting([Todo::class])
            ->sendCommand(new PostTodo(
                self::UUID,
                self::TITLE,
            ))
            ->getAggregate(Todo::class, $identifier)
            ->title();

        self::assertEquals($expectedTitle, $titleFromAggregate);
    }

    /**
     * @test
     */
    public function can_assign_a_new_title(): void
    {
        //self::markTestIncomplete('Implement');
        $identifier = TodoId::fromString(self::UUID);

        $expectedChangedTitle = Title::fromString('A new title');

        /** @var Title $titleFromAggregate */
        $titleFromAggregate = EcotoneLite::bootstrapFlowTesting([Todo::class])
            ->sendCommand(new PostTodo(
                self::UUID,
                self::TITLE,
            ))
            ->sendCommand(new ChangeTitle(
                self::UUID,
                'A new title',
            ))
            ->getAggregate(Todo::class, $identifier)
            ->title();

        self::assertEquals($expectedChangedTitle, $titleFromAggregate);
    }

    /**
     * @test
     */
    public function can_assign_a_new_description(): void
    {
        self::markTestIncomplete('Implement');
    }

    /**
     * @test
     */
    public function should_return_null_as_description(): void
    {
        self::markTestIncomplete('Implement');
    }
}
