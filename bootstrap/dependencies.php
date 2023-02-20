<?php

use DI\ContainerBuilder;
use Shika\Database\Database;

use function DI\autowire;
use function DI\env;

return function (ContainerBuilder $container) {
    $container->addDefinitions(
        [
            Database::class =>
                autowire(Database::class)->constructor(env("DB_DSN"), env("DB_USERNAME", null), env("DB_PASSWORD", null)),
        ]
    );
};