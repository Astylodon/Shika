<?php

use Shika\Controllers\SiteController;
use Shika\Controllers\UserController;
use Shika\Middleware\ApiMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group("/api", function (RouteCollectorProxy $group) {
        $group->get("/users", [UserController::class, "users"]);
        $group->get("/users/{id}", [UserController::class, "user"]);

        $group->get("/sites", [SiteController::class, "sites"]);
        $group->get("/sites/{id}", [SiteController::class, "site"]);
        $group->get("/sites/{id}/referrers", [SiteController::class, "referrers"]);
        $group->get("/sites/{id}/pages", [SiteController::class, "pages"]);
    })
    ->add(ApiMiddleware::class);
};