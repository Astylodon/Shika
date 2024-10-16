<?php

namespace Shika\Controllers\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Shika\Helpers\JsonResponse;
use Shika\Repositories\SiteRepository;
use Shika\Repositories\VisitRepository;
use Slim\Exception\HttpNotFoundException;

class StatsApiController
{
    use JsonResponse;

    private SiteRepository $sites;
    private VisitRepository $visits;

    public function __construct(SiteRepository $sites, VisitRepository $visits)
    {
        $this->sites = $sites;
        $this->visits = $visits;
    }

    /**
     * Returns the top referrers on /sites/{id}/referrers
     */
    public function referrers(Request $request, Response $response, array $args)
    {
        $site = $this->sites->findById($args["id"]);
        
        if (!$site)
        {
            throw new HttpNotFoundException($request);
        }

        $from = $this->getFromTime($request);
        $referrers = $this->visits->groupBy("referrer_host", "referrer", $from, $site->id);
        
        return $this->json($response, $referrers);
    }

    /**
     * Returns the top referrers on /sites/{id}/pages
     */
    public function pages(Request $request, Response $response, array $args)
    {
        $site = $this->sites->findById($args["id"]);
        
        if (!$site)
        {
            throw new HttpNotFoundException($request);
        }

        $from = $this->getFromTime($request);
        $pages = $this->visits->groupBy("visit_path", "path", $from, $site->id);

        return $this->json($response, $pages);
    }

    /**
     * Returns the top browsers on /sites/{id}/browsers
     */
    public function browsers(Request $request, Response $response, array $args)
    {
        $site = $this->sites->findById($args["id"]);
        
        if (!$site)
        {
            throw new HttpNotFoundException($request);
        }

        $from = $this->getFromTime($request);
        $browsers = $this->visits->groupBy("browser", "browser", $from, $site->id);

        return $this->json($response, $browsers);
    }

    /**
     * Returns the top operating systems on /sites/{id}/operating-systems
     */
    public function systems(Request $request, Response $response, array $args)
    {
        $site = $this->sites->findById($args["id"]);
        
        if (!$site)
        {
            throw new HttpNotFoundException($request);
        }

        $from = $this->getFromTime($request);
        $systems = $this->visits->groupBy("operating_system", "operating_system", $from, $site->id);

        return $this->json($response, $systems);
    }

    /**
     * Returns the top referrers on /sites/{id}/device-types
     */
    public function devices(Request $request, Response $response, array $args)
    {
        $site = $this->sites->findById($args["id"]);
        
        if (!$site)
        {
            throw new HttpNotFoundException($request);
        }

        $from = $this->getFromTime($request);
        $devices = $this->visits->groupBy("device_type", "device_type", $from, $site->id);

        return $this->json($response, $devices);
    }

    /**
     * Returns the top referrers on /sites/{id}/countries
     */
    public function countries(Request $request, Response $response, array $args)
    {
        $site = $this->sites->findById($args["id"]);
        
        if (!$site)
        {
            throw new HttpNotFoundException($request);
        }

        $from = $this->getFromTime($request);
        $countries = $this->visits->groupBy("country_code", "country", $from, $site->id);

        return $this->json($response, $countries);
    }

    private function getFromTime(Request $request)
    {
        $params = $request->getQueryParams();

        if (!isset($params["from"]) || intval($params["from"]) == 0)
        {
            // default to last 7 days
            return time() - 604800;
        }

        return intval($params["from"]);
    }
}