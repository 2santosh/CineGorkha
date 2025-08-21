<?php
// HomeController.php

// Include core classes
require_once __DIR__ . '/../core/Database.php';

// Optionally define base path for includes
define('BASE_PATH', __DIR__ . '/../public/views/');

// Include models
require_once __DIR__ . '/../models/Movie.php';

class HomeController {
    public function index() {
        // Create database connection
        $database = new Database();
        $db = $database->getConnection();

        // Get latest movies
        $movie = new Movie($db);
        $stmt = $movie->readAll(); // Fetch movies as array or PDOStatement

        // Include header
        require_once BASE_PATH . 'components/header.php';

        // Include home view
        require_once BASE_PATH . 'home.php';

        // Include footer
        require_once BASE_PATH . 'components/footer.php';
    }
}
?>
