<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Application\Domain\Todo;

class RegisterProductCommand
{
    public function __construct(
        private int $productId,
        private int $cost
    ) {
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getCost(): int
    {
        return $this->cost;
    }
}
