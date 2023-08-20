<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Infrastructure;

use Ecotone\Dbal\Configuration\DbalConfiguration;
use Ecotone\JMSConverter\JMSConverterConfiguration;
use Ecotone\Messaging\Attribute\ServiceContext;

class Configuration
{
    //    #[ServiceContext]
    //    public function getJmsConfiguration()
    //    {
    //        return JMSConverterConfiguration::createWithDefaults()
    //            ->withDefaultNullSerialization(false)
    //            ->withNamingStrategy("identicalPropertyNamingStrategy");
    //    }

    #[ServiceContext]
    public function configuration()
    {
        return DbalConfiguration::createWithDefaults()
            ->withTransactionOnCommandBus(false)
            ->withTransactionOnAsynchronousEndpoints(false)
            ->withDocumentStore(inMemoryDocumentStore: true);
    }
}
