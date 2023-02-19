<?php

namespace Shika\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AnalyticsController
{
    public function __construct(ContainerInterface $container)
    {
    }

    public function send(Request $request, Response $response)
    {
        return $response->withStatus(204);
    }
}