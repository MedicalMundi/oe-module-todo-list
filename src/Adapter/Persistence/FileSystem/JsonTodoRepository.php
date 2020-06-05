<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Adapter\Persistence\FileSystem;

use Doctrine\Common\Collections\ArrayCollection;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\AddTodoPort;
use MedicalMundi\TodoList\Application\Port\Out\Persistence\LoadTodoPort;
use MedicalMundi\TodoList\Domain\Todo\Exception\CouldNotRetrieveTodo;
use MedicalMundi\TodoList\Domain\Todo\Exception\CouldNotSaveTodo;
use MedicalMundi\TodoList\Domain\Todo\Todo;
use MedicalMundi\TodoList\Domain\Todo\TodoId;

class JsonTodoRepository implements AddTodoPort, LoadTodoPort
{
    /** @var string */
    private $filename;

    /** @var ArrayCollection<string, Todo> $todos */
    private $todos;

    /** @var TodoDataTransformer */
    private $transformer;

    /**
     * JsonTodoRepository constructor.
     * @param string $filename
     */
    public function __construct(string $filename='todos.json')
    {
        $dataDir = \dirname(__DIR__);
        $this->filename = $dataDir.'/'.$filename;
        $this->transformer = new TodoDataTransformer();
        $this->todos= new arrayCollection();
    }

    private function writeData(): void
    {
        $data = [];
        $todosList = $this->todos->toArray();

        foreach ($todosList as $todo) {
            $data[] = $this->todoToArray($todo);
        }
        $jsonData = json_encode($data);
        file_put_contents($this->filename, $jsonData);
    }


    private function todoToArray(Todo $todo): array
    {
        $key = $todo->id()->toString();
        $value = [
            'id' => $todo->id()->toString(),
            'title' => $todo->title()->toString()
        ];

        return [
            $key => $value
        ];
    }

    private function readData(): void
    {
        $jsonData = file_get_contents($this->filename);
        $data = array_values(json_decode($jsonData, true));
        $newCollection = new arrayCollection();
        foreach ($data as $key => $value) {
            $id = (string) array_values(array_values($value)[0])[0] ;

            $newCollection->set((string)$id, $this->transformer->transformFromArray($value));
        }
        $this->todos = $newCollection;
    }

    private function containsKey(TodoId $todoId): bool
    {
        return $this->todos->containsKey($todoId->toString());
    }

    /**
     * @param Todo $todo
     * @throws CouldNotSaveTodo
     */
    public function addTodo(Todo $todo): void
    {
        if ($this->containsKey($todo->id())) {
            throw CouldNotSaveTodo::becauseDuplicateTodoIdDetected($todo->id()->toString());
        }

        try {
            $this->todos->set($todo->id()->toString(), $todo);
            $this->writeData();
        } catch (\Exception $exception) {
            throw CouldNotSaveTodo::becauseDatabaseError(0, $exception);
        }
    }

    /**
     * @param TodoId $todoId
     * @return Todo
     * @throws CouldNotRetrieveTodo
     */
    public function withTodoId(TodoId $todoId): Todo
    {
        $this->readData();
        if (!$todo = $this->todos->get($todoId->toString())) {
            throw CouldNotRetrieveTodo::becauseTodoNotExist($todoId->toString());
        }

        return $todo;
    }
}
