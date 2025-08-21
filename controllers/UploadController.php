<?php
/**
 * UploadController.php
 *
 * This controller handles functionalities related to movie uploads.
 * It provides methods for displaying the upload form and processing submitted movie data
 * and files from users with the 'uploader' role.
 */

class UploadController
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
     * Constructor: Initializes the UploadController with database and session instances.
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
     * Displays the movie upload form.
     * Corresponds to the '/uploader/upload' route (GET request).
     */
    public function showUploadForm()
    {
        // Access control: Only allow logged-in users with 'uploader' or 'admin' role
        if (!$this->session->isLoggedIn() || !$this->session->hasRole('uploader') && !$this->session->hasRole('admin')) {
            $this->session->setFlash('error', 'Access denied. You must be an uploader or administrator to upload movies.', 'error');
            header('Location: /login'); // Redirect to login or unauthorized page
            exit;
        }

        require_once BASE_PATH . 'public/views/uploader/upload_form.php';
    }

    /**
     * Processes the submitted movie upload form, including file uploads.
     * Corresponds to the '/uploader/upload' route (POST request).
     */
    public function processUpload()
    {
        // Ensure this is a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->session->setFlash('upload_message', 'Invalid request method.', 'error');
            $this->session->setFlash('upload_status', 'error'); // For styling
            header('Location: /uploader/upload');
            exit;
        }

        // Access control: Only allow logged-in users with 'uploader' or 'admin' role
        if (!$this->session->isLoggedIn() || !$this->session->hasRole('uploader') && !$this->session->hasRole('admin')) {
            $this->session->setFlash('upload_message', 'Access denied. You must be an uploader or administrator to upload movies.', 'error');
            $this->session->setFlash('upload_status', 'error');
            header('Location: /login');
            exit;
        }

        // 1. Validate incoming data
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $releaseYear = filter_var($_POST['release_year'] ?? '', FILTER_VALIDATE_INT);
        $director = trim($_POST['director'] ?? '');
        $genre = trim($_POST['genre'] ?? '');
        $duration = filter_var($_POST['duration'] ?? '', FILTER_VALIDATE_INT);
        $uploaderId = $this->session->getUserId(); // Get the ID of the logged-in uploader

        // Basic form field validation
        if (empty($title) || empty($description) || $releaseYear === false || empty($genre) || $duration === false || empty($uploaderId)) {
            $this->session->setFlash('upload_message', 'Please fill in all required text fields correctly.', 'error');
            $this->session->setFlash('upload_status', 'error');
            header('Location: /uploader/upload');
            exit;
        }

        // 2. Handle Poster Image Upload
        $posterFile = $_FILES['poster'] ?? null;
        $posterPath = null;
        $posterUploadSuccess = false;

        if ($posterFile && $posterFile['error'] === UPLOAD_ERR_OK) {
            $posterFileName = uniqid('poster_', true) . '.' . pathinfo($posterFile['name'], PATHINFO_EXTENSION);
            $posterDestination = POSTER_UPLOAD_DIR . $posterFileName;

            // Validate poster file type and size
            if (!in_array($posterFile['type'], ALLOWED_IMAGE_TYPES)) {
                $this->session->setFlash('upload_message', 'Invalid poster file type. Only JPEG, PNG, GIF are allowed.', 'error');
                $this->session->setFlash('upload_status', 'error');
                header('Location: /uploader/upload');
                exit;
            }
            if ($posterFile['size'] > MAX_POSTER_SIZE) {
                $this->session->setFlash('upload_message', 'Poster file too large. Max ' . (MAX_POSTER_SIZE / (1024 * 1024)) . 'MB.', 'error');
                $this->session->setFlash('upload_status', 'error');
                header('Location: /uploader/upload');
                exit;
            }

            if (move_uploaded_file($posterFile['tmp_name'], $posterDestination)) {
                $posterPath = str_replace(BASE_PATH . 'public/', '', $posterDestination); // Store path relative to public/
                $posterUploadSuccess = true;
            } else {
                $this->session->setFlash('upload_message', 'Failed to upload movie poster.', 'error');
                $this->session->setFlash('upload_status', 'error');
                header('Location: /uploader/upload');
                exit;
            }
        } else {
            $this->session->setFlash('upload_message', 'No poster file uploaded or an upload error occurred.', 'error');
            $this->session->setFlash('upload_status', 'error');
            header('Location: /uploader/upload');
            exit;
        }

        // 3. Handle Video File Upload
        $videoFile = $_FILES['video_file'] ?? null;
        $videoPath = null;
        $videoUploadSuccess = false;

        if ($videoFile && $videoFile['error'] === UPLOAD_ERR_OK) {
            $videoFileName = uniqid('video_', true) . '.' . pathinfo($videoFile['name'], PATHINFO_EXTENSION);
            $videoDestination = VIDEO_UPLOAD_DIR . $videoFileName;

            // Validate video file type and size
            if (!in_array($videoFile['type'], ALLOWED_VIDEO_TYPES)) {
                $this->session->setFlash('upload_message', 'Invalid video file type. Only MP4, WebM are allowed.', 'error');
                $this->session->setFlash('upload_status', 'error');
                // Clean up poster if video failed
                if ($posterUploadSuccess && file_exists($posterDestination)) {
                    unlink($posterDestination);
                }
                header('Location: /uploader/upload');
                exit;
            }
            if ($videoFile['size'] > MAX_VIDEO_SIZE) {
                $this->session->setFlash('upload_message', 'Video file too large. Max ' . (MAX_VIDEO_SIZE / (1024 * 1024)) . 'MB.', 'error');
                $this->session->setFlash('upload_status', 'error');
                // Clean up poster if video failed
                if ($posterUploadSuccess && file_exists($posterDestination)) {
                    unlink($posterDestination);
                }
                header('Location: /uploader/upload');
                exit;
            }

            if (move_uploaded_file($videoFile['tmp_name'], $videoDestination)) {
                $videoPath = str_replace(BASE_PATH . 'public/', '', $videoDestination); // Store path relative to public/
                $videoUploadSuccess = true;
            } else {
                $this->session->setFlash('upload_message', 'Failed to upload movie video.', 'error');
                $this->session->setFlash('upload_status', 'error');
                // Clean up poster if video failed
                if ($posterUploadSuccess && file_exists($posterDestination)) {
                    unlink($posterDestination);
                }
                header('Location: /uploader/upload');
                exit;
            }
        } else {
            $this->session->setFlash('upload_message', 'No video file uploaded or an upload error occurred.', 'error');
            $this->session->setFlash('upload_status', 'error');
            // Clean up poster if video failed
            if ($posterUploadSuccess && file_exists($posterDestination)) {
                unlink($posterDestination);
            }
            header('Location: /uploader/upload');
            exit;
        }

        // 4. Save movie data to the database
        if ($posterUploadSuccess && $videoUploadSuccess) {
            if ($this->movieModel->createMovie($title, $description, $releaseYear, $director, $genre, $duration, $posterPath, $videoPath, $uploaderId)) {
                $this->session->setFlash('upload_message', 'Movie uploaded successfully!', 'success');
                $this->session->setFlash('upload_status', 'success');
                header('Location: /uploader/manage'); // Redirect to manage movies page
                exit;
            } else {
                $this->session->setFlash('upload_message', 'Failed to save movie data to database. Please try again.', 'error');
                $this->session->setFlash('upload_status', 'error');
                // If database insert fails, clean up uploaded files
                if (file_exists($posterDestination)) unlink($posterDestination);
                if (file_exists($videoDestination)) unlink($videoDestination);
                header('Location: /uploader/upload');
                exit;
            }
        } else {
            // This case should ideally be caught by previous checks, but as a fallback
            $this->session->setFlash('upload_message', 'An unexpected file upload error occurred.', 'error');
            $this->session->setFlash('upload_status', 'error');
            // Ensure any partially uploaded files are removed
            if ($posterUploadSuccess && file_exists($posterDestination)) unlink($posterDestination);
            if ($videoUploadSuccess && file_exists($videoDestination)) unlink($videoDestination);
            header('Location: /uploader/upload');
            exit;
        }
    }
}
