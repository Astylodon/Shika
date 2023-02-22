<?php

namespace Shika\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Shika\Repositories\SiteRepository;
use Shika\Repositories\VisitRepository;

class AnalyticsController
{
    private VisitRepository $visits;
    private SiteRepository $sites;

    public function __construct(VisitRepository $visits, SiteRepository $sites)
    {
        $this->visits = $visits;
        $this->sites = $sites;
    }

    public function send(Request $request, Response $response)
    {
        $body = $request->getBody()->getContents();
        $data = json_decode($body);

        if ($data === null)
        {
            return $response->withStatus(400);
        }

        // find the site for the key
        $site = $this->sites->findByKey($data->siteKey);

        if ($site === null)
        {
            return $response->withStatus(404);
        }

        return $response->withStatus(204);
    }
}