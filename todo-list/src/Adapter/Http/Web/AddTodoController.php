<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Adapter\Http\Web;

use MedicalMundi\TodoList\Application\Port\In\AddTodoUseCase;

class AddTodoController
{
    /** @var AddTodoUseCase */
    private $useCaseService;

    /**
     * AddTodoController constructor.
     * @param AddTodoUseCase $useCaseService
     */
    public function __construct(AddTodoUseCase $useCaseService)
    {
        $this->useCaseService = $useCaseService;
    }

    public function __invoke()
    {
        // TODO: Implement __invoke() method.

        return "Add Todo Controller.";
    }
}
