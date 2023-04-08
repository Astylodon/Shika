<?php

namespace Shika\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Shika\Helpers\Session;
use Shika\Helpers\Twig;
use Shika\Repositories\ApiKeyRepository;

class PageController
{
    private Twig $twig;
    private Session $session;

    private ApiKeyRepository $keys;

    public function __construct(Twig $twig, Session $session, ApiKeyRepository $keys)
    {
        $this->twig = $twig;
        $this->session = $session;
        
        $this->keys = $keys;
    }

    public function index(Request $request, Response $response)
    {
        return $this->twig->render($response, "dashboard/index.html.twig", [ "username" => $this->session->get("user.name") ]);
    }

    public function keys(Request $request, Response $response)
    {
        return $this->twig->render($response, "dashboard/keys.html.twig", [ "keys" => $this->keys->getKeys() ]);
    }
}