<?php
class AdminController {
    public function index() {
        // Check if user is logged in and has admin role
        $session = new Session();
        if (!$session->isAdmin()) {
            header('Location: /login');
            exit;
        }
        
        // Include header
        require_once '../public/views/components/header.php';
        
        // Include admin dashboard view
        require_once '../public/views/admin/admin_dashboard.php';
        
        // Include footer
        require_once '../public/views/components/footer.php';
    }
    
    public function manageUsers() {
        // Check if user is logged in and has admin role
        $session = new Session();
        if (!$session->isAdmin()) {
            header('Location: /login');
            exit;
        }
        
        // Include models
        require_once '../models/User.php';
        
        // Create database connection
        $database = new Database();
        $db = $database->getConnection();
        
        // Get all users
        $user = new User($db);
        $stmt = $user->readAll();
        
        // Include header
        require_once '../public/views/components/header.php';
        
        // Include manage users view
        require_once '../public/views/admin/manage_users.php';
        
        // Include footer
        require_once '../public/views/components/footer.php';
    }
    
    public function manageContent() {
        // Check if user is logged in and has admin role
        $session = new Session();
        if (!$session->isAdmin()) {
            header('Location: /login');
            exit;
        }
        
        // Include models
        require_once '../models/Movie.php';
        
        // Create database connection
        $database = new Database();
        $db = $database->getConnection();
        
        // Get all movies
        $movie = new Movie($db);
        $stmt = $movie->readAll();
        
        // Include header
        require_once '../public/views/components/header.php';
        
        // Include manage content view
        require_once '../public/views/admin/manage_content.php';
        
        // Include footer
        require_once '../public/views/components/footer.php';
    }
}
?>