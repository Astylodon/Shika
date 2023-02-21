<?php

namespace Shika\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Shika\Repositories\VisitRepository;

class AnalyticsController
{
    private VisitRepository $visits;

    public function __construct(VisitRepository $visits)
    {
        $this->visits = $visits;
    }

    public function send(Request $request, Response $response)
    {
        return $response->withStatus(204);
    }
}