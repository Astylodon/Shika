<?php

use Shika\Controllers\Api\SiteApiController;
use Shika\Controllers\Api\StatsApiController;
use Shika\Controllers\Api\UserApiController;
use Shika\Middleware\ApiMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group("/api", function (RouteCollectorProxy $group) {
        $group->get("/users", [UserApiController::class, "users"]);
        $group->get("/users/{id}", [UserApiController::class, "user"]);

        $group->get("/sites", [SiteApiController::class, "sites"]);
        $group->get("/sites/{id}", [SiteApiController::class, "site"]);

        $group->get("/sites/{id}/referrers", [StatsApiController::class, "referrers"]);
        $group->get("/sites/{id}/pages", [StatsApiController::class, "pages"]);
        $group->get("/sites/{id}/browsers", [StatsApiController::class, "browsers"]);
        $group->get("/sites/{id}/operating-systems", [StatsApiController::class, "systems"]);
        $group->get("/sites/{id}/device-types", [StatsApiController::class, "devices"]);
        $group->get("/sites/{id}/countries", [StatsApiController::class, "countries"]);
    })
    ->add(ApiMiddleware::class);
};