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

        // validate the href
        if ($location === false || !isset($location["host"]) || !isset($location["path"]))
        {
            return $this->json($response, [ "error" => "Invalid href" ])->withStatus(400);
        }

        // build the visit
        $visit = [
            "site_id" => $site->id,
            "visit_at" => gmdate("Y-m-d H:i:s"),

            "visit_host" => $location["host"],
            "visit_path" => $location["path"],
        ];

        // add the referrer if we have one
        if ($referrer !== false && isset($referrer["host"]))
        {
            $visit["referrer_host"] = $referrer["host"];

            if (isset($referrer["path"]) && $referrer["path"] != "/")
            {
                $visit["referrer_path"] = $referrer["path"];
            }
        }

        // insert the visit
        $this->visits->addVisit($visit);

        return $response->withStatus(200);
    }
}