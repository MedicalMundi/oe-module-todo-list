<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Application\Domain\Todo;

use Ecotone\Modelling\Attribute\Aggregate;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\Identifier;
use Ecotone\Modelling\WithEvents;
use JsonSerializable;
use MedicalMundi\TodoList\Application\Domain\Todo\Command\ChangeTitle;
use MedicalMundi\TodoList\Application\Domain\Todo\Command\PostTodo;
use MedicalMundi\TodoList\Application\Domain\Todo\Event\TodoWasPosted;

#[Aggregate]
class Todo implements TodoInterface, JsonSerializable
{
    use WithEvents;

    private ?Description $description = null;

    private TodoStatus $status;

    private function __construct(
        #[Identifier]
        private TodoId $todoId,
        private Title $title
    ) {
        $this->recordThat(new TodoWasPosted($this->todoId));
    }

    #[CommandHandler]
    public static function post(PostTodo $command): self
    {
        $self = new self(TodoId::fromString($command->todoId), Title::fromString($command->todoTitle));
        $self->status = TodoStatus::OPEN();

        return $self;
    }

    #[CommandHandler]
    public function changeTitle(ChangeTitle $command): void
    {
        $this->title = Title::fromString($command->todoTitle);
    }

    public function changeDescription(Description $description): void
    {
        $this->description = $description;
    }

    public function id(): TodoId
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

    public function status(): TodoStatus
    {
        return $this->status;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->todoId->toString(),
            'title' => $this->title->toString(),
            'description' => (null === $this->description) ? '' : $this->description->toString(),
            'status' => $this->status->value(),
        ];
    }
}
