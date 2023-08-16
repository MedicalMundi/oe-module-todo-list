<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Tests\Unit\Todo;

use MedicalMundi\TodoList\Domain\Todo\Description;
use PHPUnit\Framework\TestCase;

class DescriptionTest extends TestCase
{
    private const VALID_DESCRIPTION = 'A valid description';

    private const INVALID_DESCRIPTION = '';

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function can_be_created(): void
    {
        Description::fromString(self::VALID_DESCRIPTION);
    }

    /**
     * @test
     */
    public function should_return_value(): void
    {
        $description = Description::fromString(self::VALID_DESCRIPTION);

        self::assertEquals(self::VALID_DESCRIPTION, $description->value());
    }

    /**
     * @test
     */
    public function should_return_value_as_string(): void
    {
        $description = Description::fromString(self::VALID_DESCRIPTION);

        self::assertEquals(self::VALID_DESCRIPTION, $description->toString());
        self::assertEquals(self::VALID_DESCRIPTION, $description->__toString());
    }

    /**
     * @test
     */
    public function can_be_compared(): void
    {
        $first = Description::fromString(self::VALID_DESCRIPTION);
        $second = Description::fromString('A second description');
        $copyOfFirst = Description::fromString(self::VALID_DESCRIPTION);

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
        $this->expectExceptionMessage(sprintf('Description should be min %s char and max %s char.', Description::MIN_LENGHT, Description::MAX_LENGHT));

        Description::fromString(self::INVALID_DESCRIPTION);
    }

    /**
     * @test
     */
    public function long_values_should_throw_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Description should be min %s char and max %s char.', Description::MIN_LENGHT, Description::MAX_LENGHT));

        $longDescription = str_repeat('x', (int) (Description::MAX_LENGHT + 1));

        Description::fromString($longDescription);
    }

    /**
     * @test
     */
    public function invalid_values_should_throw_exception(): void
    {
        self::markTestIncomplete('Add dataprovider, check invalid char, sql injection, ecc..');
    }
}
