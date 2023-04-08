<?php

namespace Shika\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Shika\Helpers\JsonResponse;
use Shika\Helpers\Twig;
use Shika\Repositories\SiteRepository;

class SiteController
{
    use JsonResponse;

    private SiteRepository $sites;
    private Twig $twig;

    public function __construct(SiteRepository $sites, Twig $twig)
    {
        $this->sites = $sites;
        $this->twig = $twig;
    }

    public function sites(Request $request, Response $response)
    {
        return $this->twig->render($response, "dashboard/sites.html.twig", [ "sites" => $this->sites->getSites() ]);
    }

    public function add(Request $request, Response $response)
    {
        $body = $request->getParsedBody();

        if ($body === null || !isset($body["name"]))
        {
            return $this->twig->render($response, "dashboard/sites.html.twig", [ "sites" => $this->sites->getSites() ]);
        }

        // generate a site key
        do
        {
            $key = bin2hex(random_bytes(12));
        }
        while ($this->sites->findByKey($key) !== null);

        // add the site
        $this->sites->addSite($body["name"], $key);
        
        return $this->twig->render($response, "dashboard/sites.html.twig", [ "sites" => $this->sites->getSites() ]);
    }
}