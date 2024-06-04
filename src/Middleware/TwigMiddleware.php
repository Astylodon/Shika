<?php

namespace Shika\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Shika\Twig\RequestVariable;
use Shika\Twig\Twig;

class TwigMiddleware
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        // Set the request variable
        $this->twig->setGlobal("request",
            new RequestVariable(
                $request->getUri()->getPath(),
                $request->getAttribute("user")
            )   
        );

        return $handler->handle($request);
    }
}