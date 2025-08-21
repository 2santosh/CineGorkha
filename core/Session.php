<?php
class Session {
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    // Set session value
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    // Get session value
    public function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
    
    // Remove session value
    public function remove($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    // Destroy session
    public function destroy() {
        session_destroy();
    }
    
    // Check if user is logged in
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    // Check if user is admin
    public function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
    
    // Check if user is uploader
    public function isUploader() {
        return isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'uploader' || $_SESSION['user_role'] === 'admin');
    }
}
?>