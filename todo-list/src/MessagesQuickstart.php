<?php declare(strict_types=1);

namespace MedicalMundi\TodoList;

use Ecotone\Modelling\CommandBus;
use Ecotone\Modelling\QueryBus;
use MedicalMundi\TodoList\Application\Domain\Todo\GetProductPriceQuery;
use MedicalMundi\TodoList\Application\Domain\Todo\RegisterProductCommand;

class MessagesQuickstart
{
    public function __construct(
        private CommandBus $commandBus,
        private QueryBus $queryBus
    ) {
    }

    public function run(): void
    {
        $this->commandBus->send(new RegisterProductCommand(1, 100));

        echo $this->queryBus->send(new GetProductPriceQuery(1));
        echo "\n" . "Messages: step 1 setup: OK ";
    }
}
