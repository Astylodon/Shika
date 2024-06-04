<?php

namespace Shika\Twig;

/**
 * Exposes some information about the current request to Twig, like the path and current user.
 */
class RequestVariable
{
    public function __construct(private string $path, private mixed $user)
    {
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getUser()
    {
        return $this->user;
    }
}