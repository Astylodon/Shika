<?php

namespace Shika\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Shika\Repositories\ApiKeyRepository;
use Shika\Repositories\SiteRepository;
use Shika\Twig\Twig;

class PageController
{
    private Twig $twig;
    private ApiKeyRepository $keys;
    private SiteRepository $sites;

    public function __construct(Twig $twig, ApiKeyRepository $keys, SiteRepository $sites)
    {
        $this->twig = $twig;
        $this->keys = $keys;
        $this->sites = $sites;
    }

    public function index(Request $request, Response $response)
    {
        return $this->twig->render($response, "dashboard/index.html.twig", [ "sites" => $this->sites->getSites() ]);
    }

    public function keys(Request $request, Response $response)
    {
        return $this->twig->render($response, "dashboard/keys.html.twig", [ "keys" => $this->keys->getKeys() ]);
    }
}