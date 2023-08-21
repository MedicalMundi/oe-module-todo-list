<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Domain\Setting;

class InitializeModuleSetting
{
    public function __construct(
        private int $moduleSettingId,
    ) {
    }

    public function getModuleSettingId(): int
    {
        return $this->moduleSettingId;
    }
}
