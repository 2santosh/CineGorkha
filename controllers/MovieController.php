<?php
/**
 * MovieController.php
 *
 * This controller handles public-facing movie-related functionalities,
 * such as displaying the homepage and individual movie details.
 */

class MovieController
{
    /**
     * @var Database The database connection instance.
     */
    private $db;

    /**
     * @var Session The session management instance.
     */
    private $session;

    /**
     * @var Movie The Movie model instance.
     */
    private $movieModel;

    /**
     * Constructor: Initializes the MovieController with database and session instances.
     *
     * @param Database $db The database connection object.
     * @param Session $session The session management object.
     */
    public function __construct(Database $db, Session $session)
    {
        $this->db = $db;
        $this->session = $session;
        // Initialize the Movie model
        $this->movieModel = new Movie($this->db);
    }

    /**
     * Displays the main homepage with featured movies.
     * Corresponds to the '/' route.
     */
    public function home()
    {
        // In a real application, you'd fetch actual featured movies from the database
        // For now, home.php contains placeholder movie cards.
        // $featuredMovies = $this->movieModel->getAllMovies(); // Or a subset

        // Pass data to the view if needed
        // include_once BASE_PATH . 'public/views/home.php';
        // When using a router, views are often included after data is prepared.
        // The index.php dispatcher is already set up to include home.php directly for now.
        require_once BASE_PATH . 'public/views/home.php';
    }

    /**
     * Displays the details page for a specific movie.
     * Corresponds to the '/movies/{id}' route.
     *
     * @param int $id The ID of the movie to display.
     */
    public function details($id)
    {
        $movie = $this->movieModel->findMovieById($id);

        if (!$movie) {
            // Movie not found, handle 404 or redirect
            http_response_code(404);
            // You might have a dedicated 404 view or redirect to home with a message.
            echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>
                      <meta charset='UTF-8'>
                      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                      <title>Movie Not Found</title>
                      <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
                      <style>
                          body { background-color: #1a1a1a; color: #f0f0f0; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
                          .error-box { background-color: #2a2a2a; padding: 40px; border-radius: 10px; text-align: center; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5); }
                          .error-box h1 { color: #e50914; font-size: 3em; margin-bottom: 20px; }
                          .error-box p { font-size: 1.2em; color: #bbb; }
                          .error-box a { color: #17c3ba; text-decoration: none; margin-top: 20px; display: inline-block; }
                          .error-box a:hover { text-decoration: underline; }
                      </style>
                  </head>
                  <body>
                      <div class='error-box'>
                          <h1>404 - Movie Not Found</h1>
                          <p>The movie you are looking for does not exist.</p>
                          <a href='/'>Go to Homepage</a>
                      </div>
                  </body>
                  </html>";
            return;
        }

        // If movie is found, load the movie_details view and pass the movie data
        // For now, movie_details.php has placeholders; you'd fill it with $movie data.
        require_once BASE_PATH . 'public/views/movie_details.php';
    }
}
