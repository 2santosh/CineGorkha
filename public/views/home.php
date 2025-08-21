<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieFlix - Your Ultimate Movie Destination</title>
    <!-- Link to your main CSS file for global styles -->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>

    <?php
    // Include the header component. The path is relative to index.php,
    // which is assumed to be including this home.php file.
    include 'views/components/header.php';
    ?>

    <main>
        <!-- Hero Section: Welcoming users to the site -->
        <section class="hero-section">
            <div class="hero-content">
                <h1>Welcome to MovieFlix!</h1>
                <p>Discover thousands of movies, from blockbusters to indie gems. Start watching today!</p>
                <a href="#browse" class="btn-primary">Browse Movies</a>
            </div>
        </section>

        <!-- Featured Movies Section: Displays a grid of placeholder movie cards -->
        <section id="browse" class="featured-movies">
            <h2>Featured Movies</h2>
            <div class="movie-grid">
                <!-- Placeholder for dynamic movie cards from database -->
                <div class="movie-card">
                    <img src="https://placehold.co/300x450/000000/FFFFFF?text=Movie+1" alt="Movie Title 1">
                    <h3>Movie Title 1</h3>
                    <p>Genre: Action</p>
                    <a href="movie_details.php?id=1" class="btn-secondary">View Details</a>
                </div>
                <div class="movie-card">
                    <img src="https://placehold.co/300x450/000000/FFFFFF?text=Movie+2" alt="Movie Title 2">
                    <h3>Movie Title 2</h3>
                    <p>Genre: Comedy</p>
                    <a href="movie_details.php?id=2" class="btn-secondary">View Details</a>
                </div>
                <div class="movie-card">
                    <img src="https://placehold.co/300x450/000000/FFFFFF?text=Movie+3" alt="Movie Title 3">
                    <h3>Movie Title 3</h3>
                    <p>Genre: Sci-Fi</p>
                    <a href="movie_details.php?id=3" class="btn-secondary">View Details</a>
                </div>
                <div class="movie-card">
                    <img src="https://placehold.co/300x450/000000/FFFFFF?text=Movie+4" alt="Movie Title 4">
                    <h3>Movie Title 4</h3>
                    <p>Genre: Thriller</p>
                    <a href="movie_details.php?id=4" class="btn-secondary">View Details</a>
                </div>
            </div>
        </section>

        <!-- Genres Section: Allows users to explore movies by genre -->
        <section id="genres" class="genres-section">
            <h2>Explore by Genre</h2>
            <div class="genre-list">
                <a href="#" class="genre-tag">Action</a>
                <a href="#" class="genre-tag">Comedy</a>
                <a href="#" class="genre-tag">Drama</a>
                <a href="#" class="genre-tag">Sci-Fi</a>
                <a href="#" class="genre-tag">Thriller</a>
                <a href="#" class="genre-tag">Horror</a>
                <a href="#" class="genre-tag">Animation</a>
                <a href="#" class="genre-tag">Documentary</a>
            </div>
        </section>

        <!-- Call to Action Section: Encourages user registration -->
        <section id="latest" class="cta-section">
            <h2>Stay Tuned for Latest Releases!</h2>
            <p>Don't miss out on new movies and exclusive content. Register today!</p>
            <a href="auth/register.php" class="btn-primary">Join Now</a>
        </section>
    </main>

    <?php
    // Include the footer component (you would create this in public/views/components/footer.php)
    include 'views/components/footer.php';
    ?>

    <!-- Optional: Link to a specific JavaScript file for home page functionality -->
    <script src="assets/js/home.js"></script>
</body>
</html>
