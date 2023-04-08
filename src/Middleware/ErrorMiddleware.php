<?php

namespace Shika\Middleware;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpException;
use Slim\Psr7\Response;

class ErrorMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler)
    {
        try
        {
            return $handler->handle($request);
        }
        catch (HttpException $e)
        {
            $response = new Response();
            $response->getBody()->write($e->getMessage());
            
            return $response->withStatus($e->getCode());
        }
    }
}