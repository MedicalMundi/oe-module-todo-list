<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Application\Domain\Setting;

class InitializeModuleSetting
{
    public function __construct(
        private readonly int $moduleSettingId,
    ) {
    }

    public function getModuleSettingId(): int
    {
        return $this->moduleSettingId;
    }
}
