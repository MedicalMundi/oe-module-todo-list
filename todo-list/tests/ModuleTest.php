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
        $module = Module::bootstrap();

        self::assertInstanceOf(Module::class, $module->get('module'));
    }

    /** @test */
    public function has_a_router_registered_in_container(): void
    {
        $module = Module::bootstrap();

        self::assertTrue($module->has('router'));
    }
}
