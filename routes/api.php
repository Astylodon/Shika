<?php

use Shika\Controllers\ApiController;
use Shika\Middleware\ApiMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group("/api", function (RouteCollectorProxy $group) {
        $group->get("/users", [ApiController::class, "users"]);
    })
    ->add(ApiMiddleware::class);
};