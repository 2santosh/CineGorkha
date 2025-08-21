<?php
/**
 * Movie.php
 *
 * This model represents the 'movies' table in the database.
 * It provides methods for movie-related operations like creation, retrieval,
 * updates, and deletion.
 */

class Movie
{
    /**
     * @var Database The database connection instance.
     */
    private $db;

    /**
     * Constructor: Initializes the Movie model with a database instance.
     *
     * @param Database $db The database connection object.
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Creates a new movie record in the database.
     *
     * @param string $title The movie title.
     * @param string $description The movie description/synopsis.
     * @param int $release_year The release year.
     * @param string $director The director's name.
     * @param string $genre The movie genre.
     * @param int $duration The movie duration in minutes.
     * @param string $poster_path The file path to the movie poster.
     * @param string $video_path The file path to the movie video.
     * @param int $uploader_id The ID of the user who uploaded the movie.
     * @return bool True on successful creation, false otherwise.
     */
    public function createMovie($title, $description, $release_year, $director, $genre, $duration, $poster_path, $video_path, $uploader_id)
    {
        $this->db->query('INSERT INTO movies (title, description, release_year, director, genre, duration, poster_path, video_path, uploader_id) VALUES (:title, :description, :release_year, :director, :genre, :duration, :poster_path, :video_path, :uploader_id)');
        $this->db->bind(':title', $title);
        $this->db->bind(':description', $description);
        $this->db->bind(':release_year', $release_year, PDO::PARAM_INT);
        $this->db->bind(':director', $director);
        $this->db->bind(':genre', $genre);
        $this->db->bind(':duration', $duration, PDO::PARAM_INT);
        $this->db->bind(':poster_path', $poster_path);
        $this->db->bind(':video_path', $video_path);
        $this->db->bind(':uploader_id', $uploader_id, PDO::PARAM_INT);

        return $this->db->execute();
    }

    /**
     * Finds a movie by its ID.
     *
     * @param int $id The movie ID.
     * @return array|false An associative array of movie data if found, false otherwise.
     */
    public function findMovieById($id)
    {
        $this->db->query('SELECT * FROM movies WHERE id = :id');
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        return $this->db->single();
    }

    /**
     * Retrieves all movies from the database.
     * Can be extended to include pagination, sorting, etc.
     *
     * @return array An array of associative arrays, each representing a movie.
     */
    public function getAllMovies()
    {
        $this->db->query('SELECT * FROM movies ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    /**
     * Retrieves movies uploaded by a specific user.
     *
     * @param int $uploaderId The ID of the uploader.
     * @return array An array of associative arrays, each representing a movie.
     */
    public function getMoviesByUploader($uploaderId)
    {
        $this->db->query('SELECT * FROM movies WHERE uploader_id = :uploader_id ORDER BY created_at DESC');
        $this->db->bind(':uploader_id', $uploaderId, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    /**
     * Updates an existing movie record.
     *
     * @param int $id The ID of the movie to update.
     * @param string $title The new movie title.
     * @param string $description The new movie description/synopsis.
     * @param int $release_year The new release year.
     * @param string $director The new director's name.
     * @param string $genre The new movie genre.
     * @param int $duration The new movie duration in minutes.
     * @param string|null $poster_path The new poster path, or null if not updated.
     * @param string|null $video_path The new video path, or null if not updated.
     * @return bool True on successful update, false otherwise.
     */
    public function updateMovie($id, $title, $description, $release_year, $director, $genre, $duration, $poster_path = null, $video_path = null)
    {
        $sql = 'UPDATE movies SET title = :title, description = :description, release_year = :release_year, director = :director, genre = :genre, duration = :duration';
        if ($poster_path !== null) {
            $sql .= ', poster_path = :poster_path';
        }
        if ($video_path !== null) {
            $sql .= ', video_path = :video_path';
        }
        $sql .= ' WHERE id = :id';

        $this->db->query($sql);
        $this->db->bind(':title', $title);
        $this->db->bind(':description', $description);
        $this->db->bind(':release_year', $release_year, PDO::PARAM_INT);
        $this->db->bind(':director', $director);
        $this->db->bind(':genre', $genre);
        $this->db->bind(':duration', $duration, PDO::PARAM_INT);
        if ($poster_path !== null) {
            $this->db->bind(':poster_path', $poster_path);
        }
        if ($video_path !== null) {
            $this->db->bind(':video_path', $video_path);
        }
        $this->db->bind(':id', $id, PDO::PARAM_INT);

        return $this->db->execute();
    }

    /**
     * Deletes a movie record from the database.
     *
     * @param int $id The ID of the movie to delete.
     * @return bool True on successful deletion, false otherwise.
     */
    public function deleteMovie($id)
    {
        $this->db->query('DELETE FROM movies WHERE id = :id');
        $this->db->bind(':id', $id, PDO::PARAM_INT);
        return $this->db->execute();
    }

    /**
     * Searches for movies based on a query string (title, description, genre, director).
     *
     * @param string $searchQuery The search term.
     * @return array An array of matching movies.
     */
    public function searchMovies($searchQuery)
    {
        $likeQuery = '%' . $searchQuery . '%';
        $this->db->query('SELECT * FROM movies WHERE title LIKE :query OR description LIKE :query OR genre LIKE :query OR director LIKE :query ORDER BY title ASC');
        $this->db->bind(':query', $likeQuery);
        return $this->db->resultSet();
    }
}
