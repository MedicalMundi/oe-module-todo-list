<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Application\Domain\Todo;

interface TodoInterface
{
    public function id(): TodoId;

    public function title(): Title;
}
