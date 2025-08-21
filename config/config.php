<?php
/**
 * config.php
 *
 * This file contains global configuration settings for the MovieFlix application,
 * including database credentials and other constants.
 *
 * IMPORTANT: In a production environment, this file should be secured
 * and potentially moved outside the web-accessible root if possible,
 * or its permissions should be set carefully.
 */

// Database Configuration
define('DB_HOST', 'localhost');  // Your database host (e.g., 'localhost' or '127.0.0.1')
define('DB_NAME', 'movieflix_db'); // The name of your database
define('DB_USER', 'your_db_username'); // Your database username
define('DB_PASS', 'your_db_password'); // Your database password

// Define application-wide constants
// Example: Default user role for new registrations
define('DEFAULT_USER_ROLE', 'user');

// Example: Directory for uploaded movie posters (relative to BASE_PATH)
define('POSTER_UPLOAD_DIR', BASE_PATH . 'public/assets/images/posters/');

// Example: Directory for uploaded movie video files (relative to BASE_PATH)
define('VIDEO_UPLOAD_DIR', BASE_PATH . 'public/assets/videos/');

// Max file sizes for uploads (in bytes)
define('MAX_POSTER_SIZE', 5 * 1024 * 1024); // 5 MB
define('MAX_VIDEO_SIZE', 500 * 1024 * 1024); // 500 MB (adjust as needed)

// Allowed file types for uploads
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);
define('ALLOWED_VIDEO_TYPES', ['video/mp4', 'video/webm']);

// Other potential configurations:
// define('SITE_NAME', 'MovieFlix');
// define('DEBUG_MODE', true); // false in production

// You might also consider defining paths for logs, templates, etc.
// define('LOG_PATH', BASE_PATH . 'logs/');

?>
