<?php

namespace Shika\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Shika\Helpers\JsonResponse;
use Shika\Repositories\UserRepository;
use Shika\Security\Role;
use Shika\Security\User;
use Shika\Twig\Twig;

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
        User::checkRole($request, Role::Admin);

        return $this->twig->render($response, "dashboard/users.html.twig", [ "users" => $this->users->getUsers() ]);
    }
}