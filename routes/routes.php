<?php

use Shika\Controllers\AnalyticsController;
use Slim\App;

return function (App $app) {
    $app->post("/analytics/send", [AnalyticsController::class, "send"]);
};