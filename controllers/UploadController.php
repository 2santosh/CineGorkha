<?php
class UploadController {
    public function index() {
        // Check if user is logged in and has uploader role
        $session = new Session();
        if (!$session->isUploader()) {
            header('Location: /login');
            exit;
        }
        
        // Include header
        require_once '../views/components/header.php';
        
        // Include upload form view
        require_once '../views/uploader/upload_form.php';
        
        // Include footer
        require_once '../views/components/footer.php';
    }
    
    public function process() {
        // Check if user is logged in and has uploader role
        $session = new Session();
        if (!$session->isUploader()) {
            header('Location: /login');
            exit;
        }
        
        // Process upload form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Include models
            require_once '../models/Movie.php';
            
            // Create database connection
            $database = new Database();
            $db = $database->getConnection();
            
            // Handle file uploads
            $thumbnail_path = $this->uploadFile('thumbnail', '../public/assets/images/');
            $video_path = $this->uploadFile('video', '../public/assets/videos/');
            
            if ($thumbnail_path && $video_path) {
                // Create movie
                $movie = new Movie($db);
                $movie->title = $_POST['title'];
                $movie->description = $_POST['description'];
                $movie->release_year = $_POST['release_year'];
                $movie->duration = $_POST['duration'];
                $movie->genre = $_POST['genre'];
                $movie->director = $_POST['director'];
                $movie->cast = $_POST['cast'];
                $movie->thumbnail = $thumbnail_path;
                $movie->video_url = $video_path;
                $movie->uploaded_by = $session->get('user_id');
                
                if ($movie->create()) {
                    // Upload successful, redirect to manage page
                    header('Location: /manage');
                    exit;
                } else {
                    $error = "Failed to save movie details.";
                }
            } else {
                $error = "Failed to upload files.";
            }
        }
        
        // Redirect back to upload form with error
        header('Location: /upload?error=' . urlencode($error));
        exit;
    }
    
    private function uploadFile($fieldName, $targetDir) {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        
        // Create target directory if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        // Generate unique filename
        $extension = pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $targetPath = $targetDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetPath)) {
            return 'assets/' . basename($targetDir) . '/' . $filename;
        }
        
        return false;
    }
}
?>