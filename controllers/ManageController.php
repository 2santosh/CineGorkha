<?php
class ManageController {
    public function index() {
        // Check if user is logged in and has uploader role
        $session = new Session();
        if (!$session->isUploader()) {
            header('Location: /login');
            exit;
        }
        
        // Include models
        require_once '../models/Movie.php';
        
        // Create database connection
        $database = new Database();
        $db = $database->getConnection();
        
        // Get movies uploaded by current user
        $movie = new Movie($db);
        $stmt = $movie->readAll();
        // Note: In a real application, you would filter by uploaded_by
        
        // Include header
        require_once '../public/views/components/header.php';
        
        // Include manage movies view
        require_once '../public/views/uploader/manage_movies.php';
        
        // Include footer
        require_once '../public/views/components/footer.php';
    }
}
?>