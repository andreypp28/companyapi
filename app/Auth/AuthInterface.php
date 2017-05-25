<?php

namespace WA\Auth;

/**
 * Interface AuthInterface.
 */
interface AuthInterface
{
    /**
     * Attempts to login with the given credentials.
     *
     * @param array $input Array containing the credentials (email/username and password)
     *
     * @return bool Success?
     */
    public function login($input);

    /**
     * Attempts to logout an active user
     **.
     *
     * @return bool Success?
     */
    public function logout();

    /**
     * Checks if the credentials has been throttled by too
     * much failed login attempts.
     *
     * @param array $input Array containing the credentials (email/username and password)
     *
     * @return bool Is throttled
     */
    public function isThrottled($input);

    /**
     * Checks if the given credentials corresponds to a user that exists but
     * is not confirmed.
     *
     * @param array $input Array containing the credentials (email/username and password)
     *
     * @return bool Exists and is not confirmed?
     */
    public function existsButNotConfirmed($input);

    /**
     * Resets a password of a user. The $input['token'] will tell which user.
     *
     * @param array $input Array containing 'token', 'password' and 'password_confirmation' keys
     *
     * @return bool Success
     */
    public function resetPassword($input);

    /**
     * Validate if the user is logged in/or not.
     */
    public function loggedIn();

    /**
     * Get the User if exists.
     *
     * @return object of user
     */
    public function user();
}
