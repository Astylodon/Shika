<?php

namespace Shika\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Shika\Helpers\JsonResponse;
use Shika\Helpers\Twig;
use Shika\Repositories\UserRepository;

class UserController
{
    use JsonResponse;

    private UserRepository $users;
    private Twig $twig;

    public function __construct(UserRepository $users, Twig $twig)
    {
        $this->users = $users;
        $this->twig = $twig;
    }

    public function users(Request $request, Response $response)
    {
        return $this->twig->render($response, "dashboard/users.html.twig", [ "users" => $this->users->getUsers() ]);
    }
}