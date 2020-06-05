<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Adapter\Persistence\FileSystem;

use MedicalMundi\TodoList\Domain\Todo\Title;
use MedicalMundi\TodoList\Domain\Todo\Todo;
use MedicalMundi\TodoList\Domain\Todo\TodoId;

class TodoDataTransformer
{
    public function transformFromTodo(Todo $todo): array
    {
        $data = [
            'id' => $todo->id()->toString(),
            'title' => $todo->title()->toString()
        ];

        return $data;
    }

    /**
     * @param array <int, mixed> $todoArray
     * @return Todo
     */
    public function transformFromArray(array $todoArray): Todo
    {
        $data = array_values($todoArray)[0];
        //TODO CHECK MINIMUM DATA EXIST ID AND TITLE
        $todo = new Todo(
            TodoId::fromString((string)$data['id']),
            Title::fromString((string)$data['title'])
        );

        return $todo;
    }
}
