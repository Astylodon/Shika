<?php

use DI\Container;
use Slim\Factory\AppFactory;

require __DIR__ . "/../vendor/autoload.php";

$container = new Container();

AppFactory::setContainer($container);
$app = AppFactory::create();

$routes = require __DIR__ . "/../routes/routes.php";
$routes($app);

$app->run();