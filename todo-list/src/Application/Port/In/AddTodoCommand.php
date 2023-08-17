<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Application\Port\In;

use MedicalMundi\TodoList\Domain\Todo\Description;
use MedicalMundi\TodoList\Domain\Todo\Title;
use MedicalMundi\TodoList\Domain\Todo\TodoId;

class AddTodoCommand
{
    private TodoId $todoId;

    private Title $title;

    private ?Description $description = null;

    public function __construct(TodoId $todoId, Title $title, Description $description = null)
    {
        $this->todoId = $todoId;
        $this->title = $title;
        //TODO: fix...
        $this->description = $description ?: $description;
    }

    public function todoId(): TodoId
    {
        return $this->todoId;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function description(): ?Description
    {
        return $this->description;
    }
}
