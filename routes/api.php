<?php

use Shika\Controllers\Api\SiteApiController;
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
        $group->get("/sites/{id}/referrers", [SiteApiController::class, "referrers"]);
        $group->get("/sites/{id}/pages", [SiteApiController::class, "pages"]);
    })
    ->add(ApiMiddleware::class);
};