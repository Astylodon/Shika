<?php

namespace Shika\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Shika\Helpers\Session;
use Shika\Helpers\Twig;

class HomeController
{
    private Twig $twig;
    private Session $session;

    public function __construct(Twig $twig, Session $session)
    {
        $this->twig = $twig;
        $this->session = $session;
    }

    public function index(Request $request, Response $response)
    {
        return $this->twig->render($response, "home/index.html.twig", [ "username" => $this->session->get("user.name") ]);
    }
}