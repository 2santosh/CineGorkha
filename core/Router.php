<?php
/**
 * Router.php
 *
 * This class handles routing incoming HTTP requests to the appropriate
 * controller methods based on defined routes.
 * It supports GET and POST methods and dynamic URL parameters.
 */

class Router
{
    /**
     * @var array Stores all registered routes.
     * Each route is an associative array with 'method', 'path', and 'callback'.
     */
    private $routes = [];

    /**
     * Adds a new route to the router.
     *
     * @param string $method The HTTP method (e.g., 'GET', 'POST').
     * @param string $path The URL path, can include dynamic segments like '{id}'.
     * @param string $callback The controller and method to call (e.g., 'MovieController@home').
     */
    public function addRoute($method, $path, $callback)
    {
        $this->routes[] = [
            'method' => strtoupper($method), // Ensure method is uppercase
            'path' => $path,
            'callback' => $callback,
        ];
    }

    /**
     * Dispatches the incoming request to the matching controller method.
     *
     * @param string $uri The full request URI (e.g., '/movies/123?action=view').
     * @param Database $db The database instance.
     * @param Session $session The session instance.
     */
    public function dispatch($uri, Database $db, Session $session)
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestPath = parse_url($uri, PHP_URL_PATH); // Get path without query string

        foreach ($this->routes as $route) {
            // Convert route path to a regex pattern
            // Escape forward slashes and replace dynamic segments with regex
            $pattern = str_replace('/', '\/', $route['path']);
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $pattern);
            $pattern = '/^' . $pattern . '$/'; // Match whole string

            // Check if method matches and path matches the pattern
            if ($requestMethod === $route['method'] && preg_match($pattern, $requestPath, $matches)) {
                // Remove the full match (first element) from matches array
                array_shift($matches);

                // Extract controller and method
                list($controllerName, $methodName) = explode('@', $route['callback']);

                // Construct full class name (assuming controllers are in the global namespace for this example)
                // In a namespaced application, you'd add the namespace prefix: `\App\Controllers\\`
                $controllerFile = BASE_PATH . 'controllers/' . $controllerName . '.php';

                if (file_exists($controllerFile)) {
                    require_once $controllerFile;

                    if (class_exists($controllerName)) {
                        $controllerInstance = new $controllerName($db, $session);

                        if (method_exists($controllerInstance, $methodName)) {
                            // Call the controller method with extracted parameters
                            call_user_func_array([$controllerInstance, $methodName], $matches);
                            return; // Route found and dispatched
                        } else {
                            // Method not found in controller
                            $this->handleError(500, "Method '{$methodName}' not found in controller '{$controllerName}'.");
                            return;
                        }
                    } else {
                        // Controller class not found
                        $this->handleError(500, "Controller class '{$controllerName}' not found.");
                        return;
                    }
                } else {
                    // Controller file not found
                    $this->handleError(500, "Controller file '{$controllerFile}' not found.");
                    return;
                }
            }
        }

        // If no route matches, handle 404 Not Found
        $this->handleError(404, "Page not found for URI: " . htmlspecialchars($requestPath));
    }

    /**
     * Handles errors by setting HTTP status code and including an error view.
     *
     * @param int $statusCode The HTTP status code (e.g., 404, 500).
     * @param string $message An optional error message.
     */
    private function handleError($statusCode, $message = '')
    {
        http_response_code($statusCode);
        // You might have a specific error view (e.g., views/error.php)
        // For now, we'll just display a simple message.
        echo "<!DOCTYPE html>
              <html lang='en'>
              <head>
                  <meta charset='UTF-8'>
                  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                  <title>Error {$statusCode}</title>
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
                      <h1>Error {$statusCode}</h1>
                      <p>{$message}</p>
                      <a href='/'>Go to Homepage</a>
                  </div>
              </body>
              </html>";
    }
}
