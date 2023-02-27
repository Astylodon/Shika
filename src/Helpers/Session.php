<?php

namespace Shika\Helpers;

/**
 * Wrapper around PHP $_SESSION superglobal
 */
class Session
{
    public function __construct()
    {
        session_name("shika_session");
        session_start();
    }

    public function get(string $key)
    {
        return $_SESSION[$key];
    }

    public function set(string $key, string $value)
    {
        $_SESSION[$key] = $value;
    }

    public function has(string $key)
    {
        return isset($_SESSION[$key]);
    }
}