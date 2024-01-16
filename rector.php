<?php declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        //__DIR__ . '/config',
        __DIR__ . '/src/Adapter',
        //__DIR__ . '/tests',
        //__DIR__ . '/todo-list/src',
        //__DIR__ . '/todo-list/tests',
    ]);

    $rectorConfig->skip([
        __DIR__ . '/var',
        __DIR__ . '/vendor',
        __DIR__ . '/tools',
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_81);

    // register a single rule
    //$rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);

    // define sets of rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
    ]);
};
