<?php

use Shika\Controllers\AnalyticsController;
use Shika\Controllers\LoginController;
use Shika\Controllers\PageController;
use Shika\Controllers\SiteController;
use Shika\Controllers\UserController;
use Shika\Middleware\AuthMiddleware;
use Shika\Middleware\CorsMiddleware;
use Shika\Middleware\TwigMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // send endpoint
    $app->post("/analytics/send", [AnalyticsController::class, "send"])->add(CorsMiddleware::class);
    $app->options("/analytics/send", fn($request, $response) => $response)->add(CorsMiddleware::class);

    // login
    $app->get("/login", [LoginController::class, "show"]);
    $app->post("/login", [LoginController::class, "login"]);

    // dashboard pages
    $app->group("", function (RouteCollectorProxy $group) {
        $group->get("/", [PageController::class, "index"]);

        $group->get("/sites", [SiteController::class, "sites"]);
        $group->post("/sites", [SiteController::class, "add"]);

        $group->get("/users", [UserController::class, "users"]);
        $group->get("/users/api/keys", [PageController::class, "keys"]);
    })
    ->add(TwigMiddleware::class)
    ->add(AuthMiddleware::class);
};