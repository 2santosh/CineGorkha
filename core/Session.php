<?php
/**
 * Session.php
 *
 * This class provides a wrapper for PHP session management.
 * It helps to centralize session operations and ensure best practices.
 */

class Session
{
    /**
     * Constructor: Ensures a session is started.
     * It's safe to call session_start() multiple times; PHP will only start it once.
     */
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Sets a session variable.
     *
     * @param string $key The key for the session variable.
     * @param mixed $value The value to store.
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Gets a session variable.
     *
     * @param string $key The key for the session variable.
     * @param mixed $default The default value to return if the key does not exist.
     * @return mixed The value of the session variable, or the default value if not found.
     */
    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Checks if a session variable exists.
     *
     * @param string $key The key for the session variable.
     * @return bool True if the key exists, false otherwise.
     */
    public function exists($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Removes a session variable.
     *
     * @param string $key The key for the session variable to remove.
     */
    public function remove($key)
    {
        if ($this->exists($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroys the entire session.
     * This logs out the current user.
     */
    public function destroy()
    {
        session_unset();   // Unset all session variables
        session_destroy(); // Destroy the session
        // Clear session cookie, if any
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    }

    /**
     * Sets a flash message (a message that only persists for the next request).
     * Useful for displaying success/error messages after a redirect.
     *
     * @param string $key The key for the flash message.
     * @param string $message The message content.
     * @param string $status The status of the message (e.g., 'success', 'error').
     */
    public function setFlash($key, $message, $status = 'info')
    {
        $this->set('flash_' . $key, ['message' => $message, 'status' => $status]);
    }

    /**
     * Gets and removes a flash message.
     *
     * @param string $key The key for the flash message.
     * @return array|null An array containing 'message' and 'status', or null if not found.
     */
    public function getFlash($key)
    {
        $flashKey = 'flash_' . $key;
        if ($this->exists($flashKey)) {
            $flash = $this->get($flashKey);
            $this->remove($flashKey); // Remove it immediately after retrieval
            return $flash;
        }
        return null;
    }

    /**
     * Checks if a user is logged in.
     * Assumes 'user_id' is stored in the session upon successful login.
     *
     * @return bool True if 'user_id' exists in session, false otherwise.
     */
    public function isLoggedIn()
    {
        return $this->exists('user_id');
    }

    /**
     * Gets the logged-in user's ID.
     *
     * @return int|null The user ID or null if not logged in.
     */
    public function getUserId()
    {
        return $this->get('user_id');
    }

    /**
     * Gets the logged-in user's role.
     * Assumes 'user_role' is stored in the session upon successful login.
     *
     * @return string|null The user role or null if not logged in.
     */
    public function getUserRole()
    {
        return $this->get('user_role');
    }

    /**
     * Checks if the logged-in user has a specific role.
     *
     * @param string $role The role to check against (e.g., 'admin', 'uploader', 'user').
     * @return bool True if logged in and has the specified role, false otherwise.
     */
    public function hasRole($role)
    {
        return $this->isLoggedIn() && ($this->getUserRole() === $role);
    }
}
