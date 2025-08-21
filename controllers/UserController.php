<?php
class UserController {
    public function login() {
        // If user is already logged in, redirect to home
        $session = new Session();
        if ($session->isLoggedIn()) {
            header('Location: ' . SITE_URL);
            exit;
        }
        
        // Process login form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Include models
            require_once __DIR__ . '/../models/User.php';
            
            // Create database connection
            $database = new Database();
            $db = $database->getConnection();
            
            // Check if email exists
            $user = new User($db);
            $user->email = $_POST['email'];
            
            if ($user->emailExists() && password_verify($_POST['password'], $user->password)) {
                // Set session variables
                $session->set('user_id', $user->id);
                $session->set('username', $user->username);
                $session->set('user_role', $user->role);
                
                // Redirect to home
                header('Location: ' . SITE_URL);
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        }
        
        // Include header
        require_once __DIR__ . '/../public/views/components/header.php';
        
        // Include login view
        require_once __DIR__ . '/../public/views/auth/login.php';
        
        // Include footer
        require_once __DIR__ . '/../public/views/components/footer.php';
    }
    
    public function register() {
        // If user is already logged in, redirect to home
        $session = new Session();
        if ($session->isLoggedIn()) {
            header('Location: ' . SITE_URL);
            exit;
        }
        
        // Process registration form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Include models
            require_once __DIR__ . '/../models/User.php';
            
            // Create database connection
            $database = new Database();
            $db = $database->getConnection();
            
            // Create user
            $user = new User($db);
            $user->username = $_POST['username'];
            $user->email = $_POST['email'];
            $user->password = $_POST['password'];
            $user->role = 'user'; // Default role
            
            // Check if email already exists
            $emailCheck = new User($db);
            $emailCheck->email = $_POST['email'];
            
            if ($emailCheck->emailExists()) {
                $error = "Email already exists.";
            } else if ($user->create()) {
                // Registration successful, redirect to login
                header('Location: ' . SITE_URL . 'login');
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
        
        // Include header
        require_once __DIR__ . '/../public/views/components/header.php';
        
        // Include register view
        require_once __DIR__ . '/../public/views/auth/register.php';
        
        // Include footer
        require_once __DIR__ . '/../public/views/components/footer.php';
    }
    
    public function logout() {
        // Destroy session
        $session = new Session();
        $session->destroy();
        
        // Redirect to home
        header('Location: ' . SITE_URL);
        exit;
    }
}
?>