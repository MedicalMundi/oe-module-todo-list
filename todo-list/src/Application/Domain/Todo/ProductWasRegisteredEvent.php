<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Application\Domain\Todo;

class ProductWasRegisteredEvent
{
    public function __construct(
        private int $productId
    ) {
    }

    public function getProductId(): int
    {
        return $this->productId;
    }
}
