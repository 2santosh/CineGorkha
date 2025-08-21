<?php
class Router {
    private $routes = [];
    
    // Add route to routing table
    public function addRoute($route, $controller, $action) {
        $this->routes[$route] = ['controller' => $controller, 'action' => $action];
    }
    
    // Dispatch the route
    public function dispatch($uri) {
        // Remove query string
        $uri = parse_url($uri, PHP_URL_PATH);
        
        // Remove base path if exists
        $base_path = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
        $uri = str_replace($base_path, '', $uri);
        
        // Check if route exists
        if (array_key_exists($uri, $this->routes)) {
            $controller = $this->routes[$uri]['controller'];
            $action = $this->routes[$uri]['action'];
            
            // Create controller instance and call action
            $controllerFile = '../controllers/' . $controller . '.php';
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $controllerInstance = new $controller();
                $controllerInstance->$action();
            } else {
                $this->show404();
            }
        } else {
            // Route not found
            $this->show404();
        }
    }
    
    // Show 404 page
    private function show404() {
        http_response_code(404);
        // Correct path to 404.php (from core directory)
        require_once '../views/404.php';
        exit;
    }
}
?>