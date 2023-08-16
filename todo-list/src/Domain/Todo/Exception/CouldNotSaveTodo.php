<?php declare(strict_types=1);


namespace MedicalMundi\TodoList\Domain\Todo\Exception;

final class CouldNotSaveTodo extends \RuntimeException implements TodoException
{
    public function __construct(string $message = '', int $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function becauseTodoNotExist(string $id, int $code = 0, \Exception $previous = null): self
    {
        return new self(\sprintf('Could not save todo with Id %s does not exist.', $id), $code, $previous);
    }

    public static function becauseDuplicateTodoIdDetected(string $id, int $code = 0, \Exception $previous = null): self
    {
        return new self(\sprintf('Could not save todo with Id %s, duplicated todoId detected.', $id), $code, $previous);
    }

    public static function becauseDatabaseConnection(int $code = 0, \Exception $previous = null): self
    {
        return new self('Could not save todo, database connection is down.', $code, $previous);
    }

    public static function becauseDatabaseError(int $code = 0, \Exception $previous = null): self
    {
        return new self('Could not save todo, database error occur.', $code, $previous);
    }
}
