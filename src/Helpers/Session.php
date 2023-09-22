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
        session_set_cookie_params(["httponly" => true, "samesite" => "Strict"]);

        session_start();
    }

    /**
     * Gets a key from the session
     * 
     * @param string $key The key
     */
    public function get(string $key)
    {
        return $_SESSION[$key];
    }

    /**
     * Sets a key on the session
     * 
     * @param string $key The key to set
     * @param mixed $value The value to set
     */
    public function set(string $key, mixed $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Gets whether the session has a key set
     * 
     * @param string $key The key
     */
    public function has(string $key)
    {
        return isset($_SESSION[$key]);
    }
}