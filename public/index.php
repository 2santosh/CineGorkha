<?php
/**
 * index.php
 *
 * This is the main entry point for the MovieFlix application.
 * All incoming requests are routed through this file.
 * It initializes necessary components and dispatches to the appropriate controller/view.
 */

// 1. Error Reporting (for development, turn off or restrict in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Start Session
// This is crucial for managing user login status, roles, and temporary data.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 3. Define Base Path
// This helps ensure all includes use consistent paths relative to the project root.
define('BASE_PATH', __DIR__ . '/../'); // Points to the 'your_project_name' root directory

// 4. Include Configuration
// This file will contain database credentials and other global settings.
require_once BASE_PATH . 'config/config.php';

// 5. Include Core Classes
// These are essential components for the application's functionality.
require_once BASE_PATH . 'core/Router.php';
require_once BASE_PATH . 'core/Database.php';
require_once BASE_PATH . 'core/Session.php'; // For session management wrapper

// 6. Include Controller and Model Classes (basic autoloading for demonstration)
// In a larger project, you might use Composer's autoloader for this.
spl_autoload_register(function ($className) {
    $parts = explode('\\', $className);
    $name = end($parts); // Get the actual class name (e.g., MovieController)

    $paths = [
        BASE_PATH . 'controllers/' . $name . '.php',
        BASE_PATH . 'models/' . $name . '.php',
        // Add other paths if needed for core classes etc.
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// 7. Initialize Database Connection
// This creates a global or accessible database instance.
$db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);

// 8. Initialize Session Handler
// You can use a wrapper for more controlled session access.
$session = new Session();

// 9. Routing Logic
// This is where the URL is parsed and the correct controller action is called.
$router = new Router();

// Define Routes
// Format: addRoute(method, path, Controller@method)
// Public routes
$router->addRoute('GET', '/', 'MovieController@home');
$router->addRoute('GET', '/movies/{id}', 'MovieController@details');
$router->addRoute('GET', '/search', 'SearchController@index');
$router->addRoute('GET', '/login', 'UserController@showLogin');
$router->addRoute('POST', '/login', 'UserController@login');
$router->addRoute('GET', '/register', 'UserController@showRegister');
$router->addRoute('POST', '/register', 'UserController@register');
$router->addRoute('GET', '/logout', 'UserController@logout');


// User Dashboard Route (requires login)
$router->addRoute('GET', '/user/dashboard', 'UserController@dashboard');

// Uploader Routes (requires uploader role)
$router->addRoute('GET', '/uploader/upload', 'UploadController@showUploadForm');
$router->addRoute('POST', '/uploader/upload', 'UploadController@processUpload');
$router->addRoute('GET', '/uploader/manage', 'ManageController@listMovies');
$router->addRoute('GET', '/uploader/edit/{id}', 'ManageController@showEditForm');
$router->addRoute('POST', '/uploader/edit/{id}', 'ManageController@processEdit');
$router->addRoute('POST', '/uploader/delete/{id}', 'ManageController@deleteMovie');

// Admin Routes (requires admin role)
$router->addRoute('GET', '/admin/dashboard', 'AdminController@dashboard');
$router->addRoute('GET', '/admin/users', 'AdminController@manageUsers');
$router->addRoute('GET', '/admin/users/edit/{id}', 'AdminController@showUserEditForm');
$router->addRoute('POST', '/admin/users/edit/{id}', 'AdminController@processUserEdit');
$router->addRoute('POST', '/admin/users/delete/{id}', 'AdminController@deleteUser');
$router->addRoute('GET', '/admin/movies', 'AdminController@manageContent');
$router->addRoute('POST', '/admin/movies/delete/{id}', 'AdminController@deleteContent');
$router->addRoute('GET', '/admin/settings', 'AdminController@siteSettings');


// Dispatch the request
$router->dispatch($_SERVER['REQUEST_URI'], $db, $session);

//
// IMPORTANT: Bootstrap CSS and JS are now handled by the views themselves.
// This allows each view to include its specific assets along with Bootstrap.
// The `<!DOCTYPE html>`, `<html>`, `<head>`, and `<body>` tags are now
// present in each view (e.g., home.php, movie_details.php) rather than here,
// which is a common pattern when views are meant to be full pages.
//
?>
