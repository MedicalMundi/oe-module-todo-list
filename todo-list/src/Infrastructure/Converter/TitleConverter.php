<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Infrastructure\Converter;

use Ecotone\Messaging\Attribute\Converter;
use MedicalMundi\TodoList\Application\Domain\Todo\Title;

class TitleConverter
{
    #[Converter]
    public function convertFrom(Title $title): string
    {
        return $title->toString();
    }

    #[Converter]
    public function convertTo(string $title): Title
    {
        return Title::fromString($title);
    }
}
