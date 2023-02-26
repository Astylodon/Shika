<?php

namespace Shika\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Shika\Repositories\UserRepository;
use Shika\View\Twig;

class LoginController
{
    private UserRepository $users;
    private Twig $twig;

    public function __construct(UserRepository $users, Twig $twig)
    {
        $this->users = $users;
        $this->twig = $twig;
    }

    public function show(Request $request, Response $response)
    {
        return $this->twig->render($response, "login/login.html.twig");
    }

    public function login(Request $request, Response $response)
    {
        $params = $request->getParsedBody();

        if ($params === null || !isset($params["username"]) || !isset($params["password"]))
        {
            return $this->twig->render($response, "login/login.html.twig");
        }

        $username = $params["username"];
        $password = $params["password"];

        // find the user by username
        $user = $this->users->findByName($username);

        if ($user === null || !password_verify($password, $user->password))
        {
            return $this->twig->render($response, "login/login.html.twig", ["error" => "The username or password is incorrect"]);
        }

        // TODO set session for user

        // redirect to home
        return $response->withStatus(302)->withHeader("Location", "/");
    }
}