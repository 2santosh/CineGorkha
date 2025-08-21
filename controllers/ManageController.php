<?php
/**
 * ManageController.php
 *
 * This controller handles functionalities for managing uploaded movies,
 * specifically by users with the 'uploader' or 'admin' role.
 * It includes methods for listing, editing, and deleting movies.
 */

class ManageController
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
     * Constructor: Initializes the ManageController with database and session instances.
     *
     * @param Database $db The database connection object.
     * @param Session $session The session management object.
     */
    public function __construct(Database $db, Session $session)
    {
        $this->db = $db;
        $this->session = $session;
        $this->movieModel = new Movie($this->db);
    }

    /**
     * Displays a list of movies uploaded by the current user.
     * Corresponds to the '/uploader/manage' route (GET request).
     */
    public function listMovies()
    {
        // Access control: Only allow logged-in users with 'uploader' or 'admin' role
        if (!$this->session->isLoggedIn() || (!$this->session->hasRole('uploader') && !$this->session->hasRole('admin'))) {
            $this->session->setFlash('error', 'Access denied. You must be an uploader or administrator to manage movies.', 'error');
            header('Location: /login');
            exit;
        }

        $uploaderId = $this->session->getUserId();
        $uploadedMovies = $this->movieModel->getMoviesByUploader($uploaderId);

        // This variable will be available in the included view
        // $uploadedMovies = $uploadedMovies;

        require_once BASE_PATH . 'public/views/uploader/manage_movies.php';
    }

    /**
     * Displays the form for editing a specific movie.
     * Corresponds to the '/uploader/edit/{id}' route (GET request).
     *
     * @param int $movieId The ID of the movie to edit.
     */
    public function showEditForm($movieId)
    {
        // Access control: Only allow logged-in users with 'uploader' or 'admin' role
        if (!$this->session->isLoggedIn() || (!$this->session->hasRole('uploader') && !$this->session->hasRole('admin'))) {
            $this->session->setFlash('error', 'Access denied. You must be an uploader or administrator to edit movies.', 'error');
            header('Location: /login');
            exit;
        }

        $movie = $this->movieModel->findMovieById($movieId);

        if (!$movie) {
            $this->session->setFlash('manage_message', 'Movie not found.', 'error');
            $this->session->setFlash('manage_status', 'error');
            header('Location: /uploader/manage');
            exit;
        }

        // Additional security: Ensure the logged-in user is the uploader or an admin
        if ($movie['uploader_id'] !== $this->session->getUserId() && !$this->session->hasRole('admin')) {
            $this->session->setFlash('manage_message', 'Unauthorized access: You can only edit your own movies.', 'error');
            $this->session->setFlash('manage_status', 'error');
            header('Location: /uploader/manage');
            exit;
        }

        // $movie variable will be available in the included view for populating form fields
        // include_once BASE_PATH . 'public/views/uploader/edit_movie.php'; // You'll need to create this view
        // For now, let's just show a simple placeholder or redirect.
        // In a real application, you'd load a specific edit form view here.
        echo "<!DOCTYPE html>
              <html lang='en'>
              <head>
                  <meta charset='UTF-8'>
                  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                  <title>Edit Movie - MovieFlix</title>
                  <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
                  <style>
                      body { background-color: #1a1a1a; color: #f0f0f0; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
                      .edit-form-placeholder { background-color: #2a2a2a; padding: 40px; border-radius: 10px; text-align: center; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5); }
                      .edit-form-placeholder h1 { color: #17c3ba; font-size: 2.5em; margin-bottom: 20px; }
                      .edit-form-placeholder p { font-size: 1.2em; color: #bbb; }
                      .edit-form-placeholder a { color: #e50914; text-decoration: none; margin-top: 20px; display: inline-block; }
                      .edit-form-placeholder a:hover { text-decoration: underline; }
                  </style>
              </head>
              <body>
                  <div class='edit-form-placeholder'>
                      <h1>Edit Movie: " . htmlspecialchars($movie['title']) . "</h1>
                      <p>This is a placeholder for the movie editing form.</p>
                      <p>You would implement the actual form in <code>public/views/uploader/edit_movie.php</code>.</p>
                      <a href='/uploader/manage'>Back to Manage Movies</a>
                  </div>
              </body>
              </html>";
        exit;
    }

    /**
     * Processes the submitted form for editing a movie.
     * Corresponds to the '/uploader/edit/{id}' route (POST request).
     *
     * @param int $movieId The ID of the movie to update.
     */
    public function processEdit($movieId)
    {
        // Ensure this is a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->session->setFlash('manage_message', 'Invalid request method.', 'error');
            $this->session->setFlash('manage_status', 'error');
            header('Location: /uploader/manage');
            exit;
        }

        // Access control: Only allow logged-in users with 'uploader' or 'admin' role
        if (!$this->session->isLoggedIn() || (!$this->session->hasRole('uploader') && !$this->session->hasRole('admin'))) {
            $this->session->setFlash('manage_message', 'Access denied. You must be an uploader or administrator to edit movies.', 'error');
            $this->session->setFlash('manage_status', 'error');
            header('Location: /login');
            exit;
        }

        $movie = $this->movieModel->findMovieById($movieId);
        if (!$movie) {
            $this->session->setFlash('manage_message', 'Movie not found for editing.', 'error');
            $this->session->setFlash('manage_status', 'error');
            header('Location: /uploader/manage');
            exit;
        }

        // Additional security: Ensure the logged-in user is the uploader or an admin
        if ($movie['uploader_id'] !== $this->session->getUserId() && !$this->session->hasRole('admin')) {
            $this->session->setFlash('manage_message', 'Unauthorized access: You can only edit your own movies.', 'error');
            $this->session->setFlash('manage_status', 'error');
            header('Location: /uploader/manage');
            exit;
        }

        // 1. Validate incoming data
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $releaseYear = filter_var($_POST['release_year'] ?? '', FILTER_VALIDATE_INT);
        $director = trim($_POST['director'] ?? '');
        $genre = trim($_POST['genre'] ?? '');
        $duration = filter_var($_POST['duration'] ?? '', FILTER_VALIDATE_INT);

        // Basic form field validation
        if (empty($title) || empty($description) || $releaseYear === false || empty($genre) || $duration === false) {
            $this->session->setFlash('manage_message', 'Please fill in all required text fields correctly for editing.', 'error');
            $this->session->setFlash('manage_status', 'error');
            header('Location: /uploader/edit/' . $movieId); // Redirect back to edit form
            exit;
        }

        $posterPath = $movie['poster_path']; // Default to existing path
        $videoPath = $movie['video_path'];   // Default to existing path

        // 2. Handle Optional New Poster Image Upload
        if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
            $newPosterFile = $_FILES['poster'];
            if (!in_array($newPosterFile['type'], ALLOWED_IMAGE_TYPES) || $newPosterFile['size'] > MAX_POSTER_SIZE) {
                $this->session->setFlash('manage_message', 'Invalid or too large new poster file.', 'error');
                $this->session->setFlash('manage_status', 'error');
                header('Location: /uploader/edit/' . $movieId);
                exit;
            }

            // Delete old poster if it exists
            $oldPosterFullPath = BASE_PATH . 'public/' . $movie['poster_path'];
            if (file_exists($oldPosterFullPath) && is_file($oldPosterFullPath)) {
                unlink($oldPosterFullPath);
            }

            $posterFileName = uniqid('poster_edit_', true) . '.' . pathinfo($newPosterFile['name'], PATHINFO_EXTENSION);
            $posterDestination = POSTER_UPLOAD_DIR . $posterFileName;
            if (move_uploaded_file($newPosterFile['tmp_name'], $posterDestination)) {
                $posterPath = str_replace(BASE_PATH . 'public/', '', $posterDestination);
            } else {
                $this->session->setFlash('manage_message', 'Failed to upload new movie poster.', 'error');
                $this->session->setFlash('manage_status', 'error');
                header('Location: /uploader/edit/' . $movieId);
                exit;
            }
        }

        // 3. Handle Optional New Video File Upload
        if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
            $newVideoFile = $_FILES['video_file'];
            if (!in_array($newVideoFile['type'], ALLOWED_VIDEO_TYPES) || $newVideoFile['size'] > MAX_VIDEO_SIZE) {
                $this->session->setFlash('manage_message', 'Invalid or too large new video file.', 'error');
                $this->session->setFlash('manage_status', 'error');
                header('Location: /uploader/edit/' . $movieId);
                exit;
            }

            // Delete old video if it exists
            $oldVideoFullPath = BASE_PATH . 'public/' . $movie['video_path'];
            if (file_exists($oldVideoFullPath) && is_file($oldVideoFullPath)) {
                unlink($oldVideoFullPath);
            }

            $videoFileName = uniqid('video_edit_', true) . '.' . pathinfo($newVideoFile['name'], PATHINFO_EXTENSION);
            $videoDestination = VIDEO_UPLOAD_DIR . $videoFileName;
            if (move_uploaded_file($newVideoFile['tmp_name'], $videoDestination)) {
                $videoPath = str_replace(BASE_PATH . 'public/', '', $videoDestination);
            } else {
                $this->session->setFlash('manage_message', 'Failed to upload new movie video.', 'error');
                $this->session->setFlash('manage_status', 'error');
                header('Location: /uploader/edit/' . $movieId);
                exit;
            }
        }

        // 4. Update movie data in the database
        if ($this->movieModel->updateMovie($movieId, $title, $description, $releaseYear, $director, $genre, $duration, $posterPath, $videoPath)) {
            $this->session->setFlash('manage_message', 'Movie updated successfully!', 'success');
            $this->session->setFlash('manage_status', 'success');
            header('Location: /uploader/manage');
            exit;
        } else {
            $this->session->setFlash('manage_message', 'Failed to update movie data. Please try again.', 'error');
            $this->session->setFlash('manage_status', 'error');
            // Re-uploading failed files? This would require more complex logic.
            // For now, if DB update fails, user needs to re-upload.
            header('Location: /uploader/edit/' . $movieId);
            exit;
        }
    }

    /**
     * Deletes a movie.
     * Corresponds to the '/uploader/delete/{id}' route (POST request).
     *
     * @param int $movieId The ID of the movie to delete.
     */
    public function deleteMovie($movieId)
    {
        // Ensure this is a POST request (for security, deletions should be POST)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->session->setFlash('manage_message', 'Invalid request method for deletion.', 'error');
            $this->session->setFlash('manage_status', 'error');
            header('Location: /uploader/manage');
            exit;
        }

        // Access control: Only allow logged-in users with 'uploader' or 'admin' role
        if (!$this->session->isLoggedIn() || (!$this->session->hasRole('uploader') && !$this->session->hasRole('admin'))) {
            $this->session->setFlash('error', 'Access denied. You must be an uploader or administrator to delete movies.', 'error');
            header('Location: /login');
            exit;
        }

        $movie = $this->movieModel->findMovieById($movieId);
        if (!$movie) {
            $this->session->setFlash('manage_message', 'Movie not found for deletion.', 'error');
            $this->session->setFlash('manage_status', 'error');
            header('Location: /uploader/manage');
            exit;
        }

        // Additional security: Ensure the logged-in user is the uploader or an admin
        if ($movie['uploader_id'] !== $this->session->getUserId() && !$this->session->hasRole('admin')) {
            $this->session->setFlash('manage_message', 'Unauthorized access: You can only delete your own movies.', 'error');
            $this->session->setFlash('manage_status', 'error');
            header('Location: /uploader/manage');
            exit;
        }

        // Delete associated files from the server first
        $posterFullPath = BASE_PATH . 'public/' . $movie['poster_path'];
        $videoFullPath = BASE_PATH . 'public/' . $movie['video_path'];

        if (file_exists($posterFullPath) && is_file($posterFullPath)) {
            unlink($posterFullPath);
        }
        if (file_exists($videoFullPath) && is_file($videoFullPath)) {
            unlink($videoFullPath);
        }

        // Delete movie record from the database
        if ($this->movieModel->deleteMovie($movieId)) {
            $this->session->setFlash('manage_message', 'Movie deleted successfully!', 'success');
            $this->session->setFlash('manage_status', 'success');
        } else {
            $this->session->setFlash('manage_message', 'Failed to delete movie from database.', 'error');
            $this->session->setFlash('manage_status', 'error');
        }

        header('Location: /uploader/manage');
        exit;
    }
}
