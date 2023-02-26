<?php

use Shika\Controllers\AnalyticsController;
use Shika\Controllers\LoginController;
use Slim\App;

return function (App $app) {
    $app->post("/analytics/send", [AnalyticsController::class, "send"]);

    $app->get("/login", [LoginController::class, "show"]);
    $app->post("/login", [LoginController::class, "login"]);
};