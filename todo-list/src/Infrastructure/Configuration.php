<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Infrastructure;

use Ecotone\Dbal\Configuration\DbalConfiguration;
use Ecotone\Messaging\Attribute\ServiceContext;

class Configuration
{
    #[ServiceContext]
    public function configuration()
    {
        return DbalConfiguration::createWithDefaults()
            ->withTransactionOnCommandBus(false)
            ->withTransactionOnAsynchronousEndpoints(false)
            ->withDocumentStore(initializeDatabaseTable: true, enableDocumentStoreAggregateRepository: true);
    }
}
