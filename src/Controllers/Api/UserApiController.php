<?php

namespace Shika\Controllers\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Shika\Helpers\JsonResponse;
use Shika\Repositories\UserRepository;
use Shika\Security\Role;
use Shika\Security\User;
use Slim\Exception\HttpNotFoundException;

class UserApiController
{
    use JsonResponse;

    private UserRepository $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function users(Request $request, Response $response)
    {
        User::checkRole($request, Role::Admin);

        // Get all users
        $users = $this->users->getUsers();

        foreach ($users as $user)
            unset($user->password);

        return $this->json($response, $users);
    }

    public function user(Request $request, Response $response, array $args)
    {
        User::checkRole($request, Role::Admin);

        // Find the user
        $user = $this->users->findById($args["id"]);

        if (!$user)
        {
            throw new HttpNotFoundException($request);
        }

        unset($user->password);

        return $this->json($response, $user);
    }
}