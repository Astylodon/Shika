<?php

namespace Shika\Helpers;

use Psr\Http\Message\ResponseInterface;

trait JsonResponse
{
    public function json(ResponseInterface $response, mixed $body): ResponseInterface
    {
        $response->getBody()->write(json_encode($body));
        
        return $response->withHeader("Content-Type", "application/json");
    }
}