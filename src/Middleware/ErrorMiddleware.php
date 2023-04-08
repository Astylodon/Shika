<?php

namespace Shika\Middleware;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Shika\Helpers\JsonResponse;
use Slim\Exception\HttpException;
use Slim\Psr7\Response;

class ErrorMiddleware
{
    use JsonResponse;

    public function __invoke(Request $request, RequestHandler $handler)
    {
        try
        {
            return $handler->handle($request);
        }
        catch (HttpException $e)
        {
            $response = new Response();
            
            // write a JSON response for API endpoints
            if (substr($request->getUri()->getPath(), 0, 5) == "/api/")
            {
                $body = [ "code" => $e->getCode(), "message" => $e->getMessage() ];

                return $this->json($response, $body)->withStatus($e->getCode());
            }
            
            // write a regular response
            $response->getBody()->write($e->getMessage());

            return $response->withStatus($e->getCode());
        }
    }
}