<?php
/**
 * SearchController.php
 *
 * This controller handles movie search functionality.
 * It retrieves search queries, fetches matching movies from the database,
 * and displays the search results page.
 */

class SearchController
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
     * Constructor: Initializes the SearchController with database and session instances.
     *
     * @param Database $db The database connection object.
     * @param Session $session The session management object.
     */
    public function __construct(Database $db, Session $session)
    {
        $this->db = $db;
        $this->session = $session;
        // Initialize the Movie model for searching
        $this->movieModel = new Movie($this->db);
    }

    /**
     * Displays the search results page based on a user's query.
     * Corresponds to the '/search' route.
     */
    public function index()
    {
        $searchQuery = $_GET['query'] ?? ''; // Get the search query from the URL
        $genreFilter = $_GET['genre'] ?? ''; // Get genre filter if present from category links

        $searchResults = [];

        if (!empty($searchQuery)) {
            // If a search query is provided, use the searchMovies method
            $searchResults = $this->movieModel->searchMovies($searchQuery);
        } elseif (!empty($genreFilter)) {
            // If only a genre filter is provided (e.g., from header category links)
            // You might need a specific method in Movie model for 'getMoviesByGenre'
            // For now, let's reuse searchMovies as a simple example, assuming it can handle genre search.
            // A more robust solution would be: $searchResults = $this->movieModel->getMoviesByGenre($genreFilter);
            $searchResults = $this->movieModel->searchMovies($genreFilter); // Adjust this if you create a specific genre method
        } else {
            // If no specific query or filter, maybe display a message or all movies
            // For now, it will simply show an empty result set until a search is performed.
        }

        // Pass the search results (and the original query) to the view
        // The search_results.php view will loop through $searchResults to display them.
        require_once BASE_PATH . 'public/views/search_results.php';
    }
}
