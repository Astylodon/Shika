<?php

namespace Shika\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Shika\Helpers\Session;
use Shika\Repositories\ApiKeyRepository;
use Shika\Repositories\UserRepository;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Psr7\Response;

/**
 * Validates for a valid API key before a request
 */
class ApiMiddleware
{ 
    private ApiKeyRepository $keys;
    private UserRepository $users;

    public function __construct(ApiKeyRepository $keys, UserRepository $users)
    {
        $this->keys = $keys;
        $this->users = $users;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        $userId = $this->getUser($request);

        if ($userId)
        {
            $user = $this->users->findById($userId);

            // Attach the user to the request
            $request = $request->withAttribute("user", $user);

            return $handler->handle($request);
        }

        throw new HttpUnauthorizedException($request);
    }

    private function getUser(Request $request): ?int
    {
        $header = $request->getHeader("X-Api-Key");

        // Check for API key header
        if (count($header) > 0)
        {
            $key = $this->keys->findByKey($header[0]);

            return $key != null ? $key->user_id : null;
        }

        // Also allow access to API with a valid session
        $session = new Session();

        if ($session->has("user.id"))
        {
            return $session->get("user.id");
        }

        return null;
    }
}