<?php

use Shika\Controllers\AnalyticsController;
use Shika\Controllers\LoginController;
use Shika\Controllers\PageController;
use Shika\Middleware\AuthMiddleware;
use Shika\Middleware\CorsMiddleware;
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
        $group->get("/sites", [PageController::class, "sites"]);
        $group->get("/users", [PageController::class, "users"]);
        $group->get("/users/api/keys", [PageController::class, "keys"]);
    })
    ->add(AuthMiddleware::class);
};