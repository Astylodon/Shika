<?php

namespace Shika\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Shika\Helpers\JsonResponse;
use Shika\Repositories\SiteRepository;
use Shika\Repositories\VisitRepository;

class AnalyticsController
{
    use JsonResponse;

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
            return $this->json($response, [ "error" => "Failed to deserialize body" ])->withStatus(400);
        }

        // check for all fields in the payload
        if (!isset($data->siteKey) || !isset($data->href) || !isset($data->referrer) || !isset($data->lang))
        {
            return $this->json($response, [ "error" => "One or more fields are missing" ])->withStatus(400);
        }

        // find the site for the key
        $site = $this->sites->findByKey($data->siteKey);

        if ($site === null)
        {
            return $this->json($response, [ "error" => "No site could be found for that key" ])->withStatus(400);
        }

        $location = parse_url($data->href);
        $referrer = parse_url($data->referrer);

        return $response->withStatus(204);
    }
}