<?php

namespace Shika\Security;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpForbiddenException;

/**
 * User helpers
 */
class User
{
    /**
     * Checks if the current user has a role or a higher role
     * 
     * @param ServerRequestInterface $request The request
     * @param Role $role The role to check for
     */
    public static function hasRole(ServerRequestInterface $request, Role $role): bool
    {
        $user = $request->getAttribute("user");

        if (!$user)
        {
            throw new \Exception("No session information");
        }

        return self::userHasRole($user, $role);
    }

    /**
     * Checks if the current user has a role or a higher role, throwing if the check fails
     * 
     * @param ServerRequestInterface $request The request
     * @param Role $role The role to check for
     */
    public static function checkRole(ServerRequestInterface $request, Role $role): void
    {
        if (!self::hasRole($request, $role))
        {
            throw new HttpForbiddenException($request);
        }
    }

    /**
     * Checks if a user has a role or a higher role
     * 
     * @param mixed $user The user
     * @param Role $role The role to check for
     */
    private static function userHasRole(mixed $user, Role $role)
    {
        return $user->role >= $role->value;
    }

    // Used for Twig

    public static function isManager(mixed $user)
    {
        return self::userHasRole($user, Role::Manager);
    }

    public static function isAdmin(mixed $user)
    {
        return self::userHasRole($user, Role::Admin);
    }
}