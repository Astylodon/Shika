<?php

namespace Shika\View;

use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig
{
    private Environment $environment;

    public function __construct()
    {
        $loader = new FilesystemLoader("../views");

        $this->environment = new Environment($loader);
    }

    public function render(ResponseInterface $response, string $name, array $data = [])
    {
        $response->getBody()->write($this->environment->render($name, $data));

        return $response;
    }
}