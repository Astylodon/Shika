<?php

namespace Shika\Middleware;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Shika\Repositories\ApiKeyRepository;
use Slim\Psr7\Response;

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

        if (count($key) < 1 || !$this->keys->findByKey($key[0]))
        {
            $response = new Response();

            return $response->withStatus(401);
        }

        return $handler->handle($request);
    }
}