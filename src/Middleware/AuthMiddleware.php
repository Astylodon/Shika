<?php

namespace Shika\Middleware;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Shika\Helpers\Session;
use Slim\Psr7\Response;

/**
 * Validates for a valid user session before a request, or else redirects to the login page
 */
class AuthMiddleware
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        if (!$this->session->has("user.id"))
        {
            $response = new Response();

            // redirect to login page
            return $response->withStatus(302)->withHeader("Location", "/login");
        }

        return $handler->handle($request);
    }
}