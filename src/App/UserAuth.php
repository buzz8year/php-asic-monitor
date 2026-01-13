<?php

namespace App;

class UserAuth
{
    /**
     * Currently authenticated user
     * @var User|null
     */
    private static $authenticated_user;

    public static function clearAuthenticatedUser()
    {
        self::$authenticated_user = null;

        if (session_status() !== PHP_SESSION_ACTIVE) {
            trigger_error("Session does not active", E_USER_WARNING);
        } elseif (isset($_SESSION['user_id'])) {
            unset($_SESSION['user_id']);
        }
    }

    /**
    * Persist authenticated user to the session
    * @param User $user
     */
    public static function setAuthenticatedUser(User $user): void
    {
        self::$authenticated_user = $user;

        if (session_status() !== PHP_SESSION_ACTIVE) {
            trigger_error("Session does not active", E_USER_WARNING);
        } else {
            $_SESSION['user_id'] = $user->getId();
        }

    }

    /**
     * Returns the authenticated user.
     * Note: if no user is authenticated or the user does not exist,
     * an empty User object is returned.
     * @return User
     */
    public static function getAuthenticatedUser(): User
    {
        if (isset(self::$authenticated_user)) {
            return self::$authenticated_user;
        }

        if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
            $user = User::get($_SESSION['user_id']);
            if ($user->getId()) {
                self::$authenticated_user = $user;
                return $user;
            }
         }

        return new User();
    }

    /**
     * Returns whether the current user is authenticated
     * @return bool
     */
    public static function isUserAuthenticated(): bool
    {
        return self::getAuthenticatedUser()->getId() && true;
    }

}