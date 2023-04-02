<?php

namespace Shika\Middleware;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CorsMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler)
    {
        $response = $handler->handle($request);
        $origin = $request->getHeader("Origin");

        if (count($origin) < 1 || ($url = parse_url($origin[0])) === false)
        {
            return $response;
        }

        $host = $url["scheme"] . "://" . $url["host"] . (isset($url["port"]) ? ":" . $url["port"] : "");

        return $response
            ->withHeader("Access-Control-Allow-Origin", $host)
            ->withHeader("Access-Control-Allow-Headers", "Content-Type, User-Agent")
            ->withHeader("Access-Control-Allow-Credentials", "true"); // required due to Beacon behavior, see https://stackoverflow.com/a/44142982/9398242
    }
}