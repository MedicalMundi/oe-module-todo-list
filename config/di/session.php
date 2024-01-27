<?php declare(strict_types=1);

use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;
use Odan\Session\SessionManagerInterface;
use Psr\Container\ContainerInterface;

return [
    SessionManagerInterface::class => fn (ContainerInterface $container) => $container->get(SessionInterface::class),

    SessionInterface::class => function (ContainerInterface $container) {
        $sessionOptions = [
            'name' => 'openEmr-oe-module-todo-list',
            'cookie_samesite' => 'Lax',
            // Optional: Send cookie only over https
            'cookie_secure' => true,
            // Optional: Additional XSS protection
            // Note: This cookie is not accessible in JavaScript!
            'cookie_httponly' => false,
        ];

        return new PhpSession($sessionOptions);
    },
];
