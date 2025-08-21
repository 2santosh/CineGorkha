<?php
/**
 * UserController.php
 *
 * This controller handles all user-related functionalities,
 * including registration, login, logout, and the user dashboard.
 */

class UserController
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
     * @var User The User model instance.
     */
    private $userModel;

    /**
     * Constructor: Initializes the UserController with database and session instances.
     *
     * @param Database $db The database connection object.
     * @param Session $session The session management object.
     */
    public function __construct(Database $db, Session $session)
    {
        $this->db = $db;
        $this->session = $session;
        // Initialize the User model
        $this->userModel = new User($this->db);
    }

    /**
     * Displays the user registration form.
     * Corresponds to the '/register' route (GET request).
     */
    public function showRegister()
    {
        // If a user is already logged in, redirect them to their dashboard
        if ($this->session->isLoggedIn()) {
            header('Location: /user/dashboard');
            exit;
        }
        require_once BASE_PATH . 'public/views/auth/register.php';
    }

    /**
     * Processes the user registration form submission.
     * Corresponds to the '/register' route (POST request).
     */
    public function register()
    {
        // Ensure this is a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->session->setFlash('register_error', 'Invalid request method.', 'error');
            header('Location: /register');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Basic validation
        if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            $this->session->setFlash('register_error', 'Please fill in all fields.', 'error');
            header('Location: /register');
            exit;
        }

        if ($password !== $confirmPassword) {
            $this->session->setFlash('register_error', 'Passwords do not match.', 'error');
            header('Location: /register');
            exit;
        }

        if (strlen($password) < 6) {
            $this->session->setFlash('register_error', 'Password must be at least 6 characters long.', 'error');
            header('Location: /register');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->setFlash('register_error', 'Invalid email format.', 'error');
            header('Location: /register');
            exit;
        }

        // Check if username or email already exists
        if ($this->userModel->findUserByIdentifier($username) || $this->userModel->findUserByIdentifier($email)) {
            $this->session->setFlash('register_error', 'Username or email already taken.', 'error');
            header('Location: /register');
            exit;
        }

        // Attempt to create user with default role 'user'
        if ($this->userModel->createUser($username, $email, $password, DEFAULT_USER_ROLE)) {
            $this->session->setFlash('register_success', 'Registration successful! You can now login.', 'success');
            header('Location: /login');
            exit;
        } else {
            $this->session->setFlash('register_error', 'Registration failed. Please try again.', 'error');
            header('Location: /register');
            exit;
        }
    }

    /**
     * Displays the user login form.
     * Corresponds to the '/login' route (GET request).
     */
    public function showLogin()
    {
        // If a user is already logged in, redirect them to their dashboard
        if ($this->session->isLoggedIn()) {
            header('Location: /user/dashboard');
            exit;
        }
        require_once BASE_PATH . 'public/views/auth/login.php';
    }

    /**
     * Processes the user login form submission.
     * Corresponds to the '/login' route (POST request).
     */
    public function login()
    {
        // Ensure this is a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->session->setFlash('login_error', 'Invalid request method.', 'error');
            header('Location: /login');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Basic validation
        if (empty($username) || empty($password)) {
            $this->session->setFlash('login_error', 'Please enter username/email and password.', 'error');
            header('Location: /login');
            exit;
        }

        $user = $this->userModel->findUserByIdentifier($username);

        if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
            // Login successful
            $this->session->set('user_id', $user['id']);
            $this->session->set('username', $user['username']);
            $this->session->set('user_role', $user['role']); // Store user role in session

            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    header('Location: /admin/dashboard');
                    break;
                case 'uploader':
                    header('Location: /uploader/manage'); // Or upload page
                    break;
                case 'user':
                default:
                    header('Location: /user/dashboard');
                    break;
            }
            exit;
        } else {
            // Login failed
            $this->session->setFlash('login_error', 'Invalid username/email or password.', 'error');
            header('Location: /login');
            exit;
        }
    }

    /**
     * Logs out the current user.
     * Corresponds to the '/logout' route.
     */
    public function logout()
    {
        $this->session->destroy();
        // Redirect to homepage or login page after logout
        header('Location: /');
        exit;
    }

    /**
     * Displays the user dashboard.
     * Corresponds to the '/user/dashboard' route.
     */
    public function dashboard()
    {
        // Ensure user is logged in
        if (!$this->session->isLoggedIn()) {
            $this->session->setFlash('login_error', 'You must be logged in to view your dashboard.', 'error');
            header('Location: /login');
            exit;
        }

        // Only allow 'user' role to access this specific dashboard.
        // Admins and Uploaders might have their own dashboards handled by other controllers.
        if ($this->session->getUserRole() !== 'user') {
            // Redirect to their specific dashboard or an unauthorized page
            $this->session->setFlash('error', 'Access denied to this dashboard.', 'error');
            header('Location: /'); // Redirect to home or specific error page
            exit;
        }

        // For now, no specific data is fetched for the dashboard itself
        // You would fetch user-specific data here if needed (e.g., watch history)
        require_once BASE_PATH . 'public/views/auth/user_dashboard.php';
    }
}
