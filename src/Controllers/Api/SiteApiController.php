<?php

namespace Shika\Controllers\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Shika\Helpers\JsonResponse;
use Shika\Repositories\SiteRepository;
use Shika\Repositories\VisitRepository;
use Shika\Security\Role;
use Shika\Security\User;
use Slim\Exception\HttpNotFoundException;

class SiteApiController
{
    use JsonResponse;

    private SiteRepository $sites;
    private VisitRepository $visits;

    public function __construct(SiteRepository $sites, VisitRepository $visits)
    {
        $this->sites = $sites;
        $this->visits = $visits;
    }

    public function sites(Request $request, Response $response)
    {
        User::checkRole($request, Role::Manager);

        return $this->json($response, $this->sites->getSites());
    }

    public function site(Request $request, Response $response, array $args)
    {
        User::checkRole($request, Role::Manager);

        // Find the site
        $site = $this->sites->findById($args["id"]);
        
        if (!$site)
        {
            throw new HttpNotFoundException($request);
        }

        $site->total_visits = $this->visits->getTotalVisits($site->id);

        return $this->json($response, $site);
    }

    public function referrers(Request $request, Response $response, array $args)
    {
        $site = $this->sites->findById($args["id"]);
        
        if (!$site)
        {
            throw new HttpNotFoundException($request);
        }

        $from = $this->getFromTime($request);
        
        return $this->json($response, $this->visits->getReferrers($from, $site->id));
    }

    public function pages(Request $request, Response $response, array $args)
    {
        $site = $this->sites->findById($args["id"]);
        
        if (!$site)
        {
            throw new HttpNotFoundException($request);
        }

        $from = $this->getFromTime($request);

        return $this->json($response, $this->visits->getPages($from, $site->id));
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