<?php

namespace Shika\Security;

use Shika\Helpers\Session;

/**
 * Generates and validates CSRF tokens
 */
class CsrfToken
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Generates a new CSRF token and adds it to the session
     */
    public function generate()
    {
        $token = base64_encode(random_bytes(32));

        $this->session->set("token", $token);
    }

    /**
     * Gets the current CSRF token
     * 
     * @return string The CSRF token
     */
    public function getToken(): string
    {
        if (!$this->session->has("token"))
        {
            throw new \Exception("No CSRF token has been generated for the current session");
        }

        return $this->session->get("token");
    }

    /**
     * Validates a token with the current CSRF token
     * 
     * @param string $token The token to check
     * @return bool Whether the token matches the current token
     */
    public function validate(string $token): bool
    {
        return $this->getToken() === $token;
    }
}