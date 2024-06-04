<?php

namespace Shika\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Shika\Helpers\Session;
use Shika\Repositories\UserRepository;
use Slim\Psr7\Response;

/**
 * Validates for a valid user session before a request, or else redirects to the login page
 */
class AuthMiddleware
{
    private Session $session;
    private UserRepository $users;

    public function __construct(Session $session, UserRepository $users)
    {
        $this->session = $session;
        $this->users = $users;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        if (!$this->session->has("user.id"))
        {
            $response = new Response();

            // Redirect to the login page
            return $response->withStatus(302)->withHeader("Location", "/login");
        }

        // Find the user from the session
        $user = $this->users->findById($this->session->get("user.id"));

        $request = $request->withAttribute("user", $user);

        return $handler->handle($request);
    }
}