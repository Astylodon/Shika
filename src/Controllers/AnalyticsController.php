<?php

namespace Shika\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AnalyticsController
{
    public function __construct()
    {   
    }

    public function send(Request $request, Response $response)
    {
        return $response->withStatus(204);
    }
}