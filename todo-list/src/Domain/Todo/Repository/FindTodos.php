<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Domain\Todo\Repository;

use MedicalMundi\TodoList\Domain\Todo\Todo;

interface FindTodos
{
    /**
     * @return Todo[]
     */
    public function findTodos(): array;
}
