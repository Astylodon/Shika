<?php

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Shika\Database\Database;
use Shika\Helpers\GeoLocation;
use Shika\Helpers\Session;
use Shika\Repositories\ApiKeyRepository;
use Shika\Repositories\SiteRepository;
use Shika\Repositories\UserRepository;
use Shika\Repositories\VisitRepository;
use Shika\Twig\Twig;

use function DI\autowire;
use function DI\env;

return function (ContainerBuilder $container) {
    // Load a .env file
    $dotenv = Dotenv::createImmutable(__DIR__ . "/../");
    $dotenv->safeLoad();

    // Add all services to the container
    $container->addDefinitions(
        [
            Database::class =>
                autowire(Database::class)->constructor(env("DB_DSN"), env("DB_USERNAME", null), env("DB_PASSWORD", null)),

            ApiKeyRepository::class => autowire(ApiKeyRepository::class),
            UserRepository::class => autowire(UserRepository::class),
            VisitRepository::class => autowire(VisitRepository::class),
            SiteRepository::class => autowire(SiteRepository::class),

            Twig::class => autowire(Twig::class),
            Session::class => autowire(Session::class),

            GeoLocation::class => autowire(GeoLocation::class)->constructor(env("MAXMIND_PATH", "")),
        ]
    );
};