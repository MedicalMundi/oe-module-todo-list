<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Tests;

use MedicalMundi\TodoList\Adapter\Http\Common\UrlService;
use PHPUnit\Framework\TestCase;

class UrlServiceTest extends TestCase
{
    private const MODULE_MAIN_URL = '/interface/modules/custom_modules/oe-module-todo-list/';

    /** @test */
    public function can_be_created(): void
    {
        $isStandAloneMode = false;
        $service = new UrlService($isStandAloneMode);

        self::assertEquals(self::MODULE_MAIN_URL, $service->baseUrl());
    }

    /** @test */
    public function can_be_created_in_standAlone_mode(): void
    {
        $isStandAloneMode = true;
        $service = new UrlService($isStandAloneMode);

        self::assertEquals('/', $service->baseUrl());
    }

    /** @test */
    public function unknow_routeName_should_throw_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $isStandAloneMode = false;
        $service = new UrlService($isStandAloneMode);

        $service->renderUrl('fake-route');
    }


    /** @test */
    public function can_render_main_url(): void
    {
        $isStandAloneMode = false;
        $service = new UrlService($isStandAloneMode);

        self::assertIsString($service->renderUrl('main'));
        self::assertEquals(self::MODULE_MAIN_URL, $service->renderUrl('main'));
    }

    /** @test */
    public function can_render_about_url(): void
    {
        $isStandAloneMode = false;
        $service = new UrlService($isStandAloneMode);

        self::assertIsString($service->renderUrl('about'));
        self::assertEquals(self::MODULE_MAIN_URL.'about', $service->renderUrl('about'));
    }

    /** @test */
    public function can_render_help_url(): void
    {
        $isStandAloneMode = false;
        $service = new UrlService($isStandAloneMode);

        self::assertIsString($service->renderUrl('help'));
        self::assertEquals(self::MODULE_MAIN_URL.'help', $service->renderUrl('help'));
    }

    /** @test */
    public function can_render_settings_url(): void
    {
        $isStandAloneMode = false;
        $service = new UrlService($isStandAloneMode);

        self::assertIsString($service->renderUrl('settings'));
        self::assertEquals(self::MODULE_MAIN_URL.'settings', $service->renderUrl('settings'));
    }

    /** @test */
    public function can_render_todolist_url(): void
    {
        $isStandAloneMode = false;
        $service = new UrlService($isStandAloneMode);

        self::assertIsString($service->renderUrl('todo-list'));
        self::assertEquals(self::MODULE_MAIN_URL.'todos', $service->renderUrl('todo-list'));
    }
}
