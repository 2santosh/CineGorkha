<?php
class MovieController {
    public function show() {
        // Check if movie ID is provided
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header('Location: /');
            exit;
        }
        
        // Include models
        require_once '../models/Movie.php';
        
        // Create database connection
        $database = new Database();
        $db = $database->getConnection();
        
        // Get movie details
        $movie = new Movie($db);
        $movie->id = $_GET['id'];
        
        if (!$movie->readOne()) {
            // Movie not found
            header('Location: /');
            exit;
        }
        
        // Include header
        require_once '../views/components/header.php';
        
        // Include movie details view
        require_once '../views/movie_details.php';
        
        // Include footer
        require_once '../views/components/footer.php';
    }
}
?>