#!/usr/bin/env php
<?php

use DI\Container;
use Ecotone\Lite\EcotoneLiteConfiguration;
use Ecotone\Lite\GatewayAwareContainer;
use Ecotone\Messaging\Config\ServiceConfiguration;
use MedicalMundi\TodoList\MessagesQuickstart;


$rootCatalog = realpath(__DIR__ . "/..");
require $rootCatalog . "/vendor/autoload.php";

$container = new class() implements GatewayAwareContainer {
    private Container $container;

    public function __construct()
    {
        $this->container = new Container();
    }

    public function get($id)
    {
        return $this->container->get($id);
    }

    public function has($id)
    {
        return $this->container->has($id);
    }

    public function addGateway(string $referenceName, object $gateway): void
    {
        $this->container->set($referenceName, $gateway);
    }
};

$messagingSystem = EcotoneLiteConfiguration::createWithConfiguration(
    $rootCatalog . '/todo-list/src',
    $container,
    ServiceConfiguration::createWithDefaults()
        ->withNamespaces([
                "MedicalMundi\TodoList"
        ]),
    [],
    false
);

if (!isset($argv[1])) {
    echo "Provide command details...\n";

    return;
}

$firstArgument = $argv[1];
if ($firstArgument === "ecotone:quickstart") {
    $ecotoneQuickstart = $container->get(MessagesQuickstart::class)->run();
}

if ($firstArgument === "ecotone:list") {
    echo "Endpoint Names\n";
    foreach ($messagingSystem->list() as $consumer) {
        echo $consumer . "\n";
    }
}

if ($firstArgument === "ecotone:run") {
    if (!isset($argv[2])) {
        throw new InvalidArgumentException("Missing consumer name");
    }

    $messagingSystem->run($argv[2]);
}

echo "\n";
