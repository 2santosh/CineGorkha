<?php
class SearchController {
    public function index() {
        // Check if search query is provided
        if (!isset($_GET['q']) || empty($_GET['q'])) {
            header('Location: /');
            exit;
        }
        
        // Include models
        require_once '../models/Movie.php';
        
        // Create database connection
        $database = new Database();
        $db = $database->getConnection();
        
        // Search movies
        $movie = new Movie($db);
        $stmt = $movie->search($_GET['q']);
        
        // Include header
        require_once '../public/views/components/header.php';
        
        // Include search results view
        require_once '../public/views/search_results.php';
        
        // Include footer
        require_once '../public/views/components/footer.php';
    }
}
?>