<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Application\Domain\Todo;

use Ecotone\Modelling\Attribute\Repository;

interface TodoBusinessRepositoryInterface
{
    #[Repository]
    public function getTodo(string $todoId): Todo;

    #[Repository]
    public function findTodo(string $todoId): ?Todo;

    #[Repository]
    public function save(Todo $todo): void;
}
