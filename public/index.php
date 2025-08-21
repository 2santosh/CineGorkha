<?php
// Include configuration
require_once '../config/config.php';

// Include core classes
require_once '../core/Database.php';
require_once '../core/Session.php';
require_once '../core/Router.php';

// Start session
$session = new Session();

// Create router
$router = new Router();

// Define routes
$router->addRoute('/', 'HomeController', 'index');
$router->addRoute('/home', 'HomeController', 'index');
$router->addRoute('/movie', 'MovieController', 'show');
$router->addRoute('/search', 'SearchController', 'index');
$router->addRoute('/login', 'UserController', 'login');
$router->addRoute('/register', 'UserController', 'register');
$router->addRoute('/logout', 'UserController', 'logout');
$router->addRoute('/upload', 'UploadController', 'index');
$router->addRoute('/upload/process', 'UploadController', 'process');
$router->addRoute('/manage', 'ManageController', 'index');
$router->addRoute('/admin', 'AdminController', 'index');
$router->addRoute('/admin/users', 'AdminController', 'manageUsers');
$router->addRoute('/admin/content', 'AdminController', 'manageContent');

// Get requested URI
$request_uri = $_SERVER['REQUEST_URI'];

// Remove query string
$request_uri = parse_url($request_uri, PHP_URL_PATH);

// Remove base path if exists
$base_path = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
$request_uri = str_replace($base_path, '', $request_uri);

// Ensure the URI starts with a slash
if (empty($request_uri) || $request_uri[0] !== '/') {
    $request_uri = '/' . $request_uri;
}

// Handle root path specifically
if ($request_uri === '/public' || $request_uri === '/public/') {
    $request_uri = '/';
}

// Dispatch the request
$router->dispatch($request_uri);
?>