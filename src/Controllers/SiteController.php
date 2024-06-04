<?php

namespace Shika\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Shika\Helpers\JsonResponse;
use Shika\Repositories\SiteRepository;
use Shika\Security\CsrfToken;
use Shika\Twig\Twig;
use Slim\Exception\HttpBadRequestException;

class SiteController
{
    use JsonResponse;

    private SiteRepository $sites;
    private Twig $twig;
    private CsrfToken $token;

    public function __construct(SiteRepository $sites, Twig $twig, CsrfToken $token)
    {
        $this->sites = $sites;
        $this->twig = $twig;
        $this->token = $token;
    }

    public function sites(Request $request, Response $response)
    {
        return $this->twig->render($response, "dashboard/sites.html.twig", [ "sites" => $this->sites->getSites() ]);
    }

    public function add(Request $request, Response $response)
    {
        $body = $request->getParsedBody();

        if ($body === null || !isset($body["name"]) || !isset($body["token"]))
        {
            return $this->twig->render($response, "dashboard/sites.html.twig", [ "sites" => $this->sites->getSites() ]);
        }

        // Validate the CSRF token
        if (!$this->token->validate($body["token"]))
        {
            throw new HttpBadRequestException($request, "Invalid CSRF token");
        }

        // Generate a site key
        do
        {
            $key = bin2hex(random_bytes(12));
        }
        while ($this->sites->findByKey($key) !== null);

        // Add the site
        $this->sites->addSite($body["name"], $key);
        
        return $this->twig->render($response, "dashboard/sites.html.twig", [ "sites" => $this->sites->getSites() ]);
    }
}