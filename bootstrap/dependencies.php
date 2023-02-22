<?php

use DI\ContainerBuilder;
use Shika\Database\Database;
use Shika\Repositories\ApiKeyRepository;
use Shika\Repositories\SiteRepository;
use Shika\Repositories\UserRepository;
use Shika\Repositories\VisitRepository;

use function DI\autowire;
use function DI\env;

return function (ContainerBuilder $container) {
    $container->addDefinitions(
        [
            Database::class =>
                autowire(Database::class)->constructor(env("DB_DSN"), env("DB_USERNAME", null), env("DB_PASSWORD", null)),

            ApiKeyRepository::class => autowire(ApiKeyRepository::class),
            UserRepository::class => autowire(UserRepository::class),
            VisitRepository::class => autowire(VisitRepository::class),
            SiteRepository::class => autowire(SiteRepository::class),
        ]
    );
};