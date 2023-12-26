<?php

namespace Shika\Middleware;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Shika\Helpers\Session;
use Shika\Helpers\Twig;
use Slim\Psr7\Response;

/**
 * Validates for a valid user session before a request, or else redirects to the login page
 */
class AuthMiddleware
{
    private Session $session;
    private Twig $twig;

    public function __construct(Session $session, Twig $twig)
    {
        $this->session = $session;
        $this->twig = $twig;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        if (!$this->session->has("user.id"))
        {
            $response = new Response();

            // redirect to login page
            return $response->withStatus(302)->withHeader("Location", "/login");
        }

        $this->twig->setGlobal("current_path", $request->getUri()->getPath());
        $this->twig->setGlobal("user_username", $this->session->get("user.name"));

        return $handler->handle($request);
    }
}