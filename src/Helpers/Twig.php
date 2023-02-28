<?php

namespace Shika\Helpers;

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

    /**
     * Renders a Twig template and writes it to the response stream
     * 
     * @param ResponseInterface $response The response
     * @param string $name The name/path of the template
     * @param array $data The data to pass to the template
     */
    public function render(ResponseInterface $response, string $name, array $data = [])
    {
        $response->getBody()->write($this->environment->render($name, $data));

        return $response;
    }
}