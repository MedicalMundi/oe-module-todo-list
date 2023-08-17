<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Domain\Todo;

class Todo implements TodoInterface
{
    private TodoId $todoId;

    private Title $title;

    private ?Description $description = null;

    public function __construct(TodoId $todoId, Title $title)
    {
        $this->todoId = $todoId;
        $this->title = $title;
    }

    public function id(): TodoId
    {
        return $this->todoId;
    }

    public function changeTitle(Title $title): void
    {
        $this->title = $title;
    }

    public function changeDescription(Description $description): void
    {
        $this->description = $description;
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
