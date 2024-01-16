<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Application\Domain\Setting;

use Ecotone\Modelling\Attribute\Aggregate;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\Identifier;
use Ecotone\Modelling\WithEvents;

#[Aggregate]
class ModuleSetting
{
    use WithEvents;

    public function __construct(
        #[Identifier] private readonly int $moduleSettingId,
        private readonly string $name = 'Module Settings'
    ) {
    }

    #[CommandHandler]
    public static function register(InitializeModuleSetting $command): self
    {
        return new self($command->getModuleSettingId());
    }

    public function getName(): string
    {
        return $this->name;
    }
}
