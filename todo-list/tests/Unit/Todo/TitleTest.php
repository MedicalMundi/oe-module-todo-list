<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Tests\Unit\Todo;

use MedicalMundi\TodoList\Application\Domain\Todo\Title;
use PHPUnit\Framework\TestCase;

class TitleTest extends TestCase
{
    private const VALID_TITLE = 'A valid title';

    private const INVALID_TITLE = '';

    /**
     * @test
     */
    public function can_be_created(): void
    {
        $title = Title::fromString(self::VALID_TITLE);

        self::assertEquals(self::VALID_TITLE, $title->value());
    }

    /**
     * @test
     */
    public function should_return_value_as_string(): void
    {
        $title = Title::fromString(self::VALID_TITLE);

        self::assertEquals(self::VALID_TITLE, $title->toString());
        self::assertEquals(self::VALID_TITLE, $title->__toString());
    }

    /**
     * @test
     */
    public function can_be_compared(): void
    {
        $first = Title::fromString(self::VALID_TITLE);
        $second = Title::fromString('a secoond title');
        $copyOfFirst = Title::fromString(self::VALID_TITLE);

        self::assertFalse($first->equals($second));
        self::assertTrue($first->equals($copyOfFirst));
        self::assertFalse($second->equals($copyOfFirst));
    }

    /**
     * @test
     */
    public function short_values_should_throw_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Title should be min %s char and max %s char.', Title::MIN_LENGHT, Title::MAX_LENGHT));

        Title::fromString(self::INVALID_TITLE);
    }

    /**
     * @test
     */
    public function long_values_should_throw_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Title should be min %s char and max %s char.', Title::MIN_LENGHT, Title::MAX_LENGHT));

        $longTitle = str_repeat('x', (int) (Title::MAX_LENGHT + 1));

        Title::fromString($longTitle);
    }

    /**
     * @test
     */
    public function invalid_values_should_throw_exception(): void
    {
        self::markTestIncomplete('Add dataprovider, check invalid char, sql injection, ecc..');
    }
}
