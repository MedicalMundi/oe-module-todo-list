<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Tests;

use MedicalMundi\TodoList\Module;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    /** @test */
    public function can_be_instatiated(): void
    {
        $module = Module::bootstrap();

        self::assertInstanceOf(Module::class, $module);
    }

    /** @test */
    public function can_be_registered_in_container(): void
    {
        self::markTestIncomplete('should return false, \'module\' is a private service.');

        $module = Module::bootstrap();

        self::assertInstanceOf(Module::class, $module->get('module'));
    }

    /** @test */
    public function has_a_router(): void
    {
        self::markTestIncomplete();
        $module = Module::bootstrap();

        self::assertTrue($module->has('router'));
    }
}
