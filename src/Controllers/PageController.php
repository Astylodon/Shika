<?php

namespace Shika\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Shika\Helpers\Session;
use Shika\Helpers\Twig;
use Shika\Repositories\ApiKeyRepository;
use Shika\Repositories\SiteRepository;

class PageController
{
    private Twig $twig;
    private Session $session;
    private ApiKeyRepository $keys;
    private SiteRepository $sites;

    public function __construct(Twig $twig, Session $session, ApiKeyRepository $keys, SiteRepository $sites)
    {
        $this->twig = $twig;
        $this->session = $session;
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