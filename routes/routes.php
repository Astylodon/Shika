<?php

use Shika\Controllers\AnalyticsController;
use Shika\Controllers\PageController;
use Shika\Middleware\CorsMiddleware;
use Slim\App;

return function (App $app) {
    // send endpoint
    $app->post("/analytics/send", [AnalyticsController::class, "send"])->add(CorsMiddleware::class);
    $app->options("/analytics/send", fn($request, $response) => $response)->add(CorsMiddleware::class);

    // match all
    $app->get("/", [PageController::class, "index"]);
    $app->get("/{route:.+}", [PageController::class, "index"]);
};