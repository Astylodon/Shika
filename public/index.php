<?php

use DI\ContainerBuilder;
use Shika\Middleware\ErrorMiddleware;
use Slim\Factory\AppFactory;

require __DIR__ . "/../vendor/autoload.php";

$containerBuilder = new ContainerBuilder();

$dependencies = require __DIR__ . "/../bootstrap/dependencies.php";
$dependencies($containerBuilder);

$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();

$routes = require __DIR__ . "/../routes/routes.php";
$routes($app);

$api = require __DIR__ . "/../routes/api.php";
$api($app);

$app->add(ErrorMiddleware::class);

$app->run();