<?php

namespace Shika\Middleware;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Shika\Helpers\Session;
use Shika\Repositories\ApiKeyRepository;
use Slim\Psr7\Response;

/**
 * Validates for a valid API key before a request
 */
class ApiMiddleware
{ 
    private ApiKeyRepository $keys;

    public function __construct(ApiKeyRepository $keys)
    {
        $this->keys = $keys;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        $key = $request->getHeader("X-Api-Key");

        if (count($key) > 0 && $this->keys->findByKey($key[0]))
        {
            return $handler->handle($request);
        }

        // also allow access to API with a valid session
        if ((new Session)->has("user.id"))
        {
            return $handler->handle($request);
        }

        return (new Response)->withStatus(401);
    }
}