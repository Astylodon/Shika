<?php

namespace Shika\Helpers;

use Psr\Http\Message\ResponseInterface;

trait JsonResponse
{
    /**
     * Write a JSON response to the response stream
     * 
     * @param ResponseInterface $response The response
     * @param mixed $body The body to be serialized as response
     */
    public function json(ResponseInterface $response, mixed $body): ResponseInterface
    {
        $response->getBody()->write(json_encode($body));
        
        return $response->withHeader("Content-Type", "application/json");
    }
}