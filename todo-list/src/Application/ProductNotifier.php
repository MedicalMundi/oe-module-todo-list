<?php declare(strict_types=1);

namespace MedicalMundi\TodoList\Application;

use Ecotone\Modelling\Attribute\EventHandler;
use MedicalMundi\TodoList\Application\Domain\Todo\ProductWasRegisteredEvent;

class ProductNotifier
{
    #[EventHandler]
    public function notifyAbout(ProductWasRegisteredEvent $event): void
    {
        echo "Product with id {$event->getProductId()} was registered!\n";
    }
}
