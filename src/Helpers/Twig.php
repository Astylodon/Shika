<?php

namespace Shika\Helpers;

use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

class Twig
{
    private Environment $environment;

    public function __construct(Vite $vite)
    {
        $loader = new FilesystemLoader("../views");

        $environment = new Environment($loader);
        $environment->addFilter(new TwigFilter("vite", [$vite, "getFile"]));

        $this->environment = $environment;
    }

    /**
     * Sets a global variable available to templates
     * 
     * @param string $name The name of the global
     * @param string $value The value of the global
     */
    public function setGlobal(string $name, string $value)
    {
        $this->environment->addGlobal($name, $value);
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