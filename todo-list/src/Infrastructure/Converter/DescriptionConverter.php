<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Infrastructure\Converter;

use Ecotone\Messaging\Attribute\Converter;
use MedicalMundi\TodoList\Application\Domain\Todo\Description;

class DescriptionConverter
{
    #[Converter]
    public function convertFrom(Description $description): string
    {
        return $description->toString();
    }

    #[Converter]
    public function convertTo(string $description): Description
    {
        return Description::fromString($description);
    }
}
