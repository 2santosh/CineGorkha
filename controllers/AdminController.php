<?php
/**
 * AdminController.php
 *
 * This controller handles functionalities for the super administrator role.
 * It includes methods for managing users, all movies, and site-wide settings.
 * Strong access control is implemented to ensure only 'admin' users can access these features.
 */

class AdminController
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
     * @var Movie The Movie model instance.
     */
    private $movieModel;

    /**
     * Constructor: Initializes the AdminController with database and session instances.
     *
     * @param Database $db The database connection object.
     * @param Session $session The session management object.
     */
    public function __construct(Database $db, Session $session)
    {
        $this->db = $db;
        $this->session = $session;
        $this->userModel = new User($this->db);
        $this->movieModel = new Movie($this->db);
    }

    /**
     * Ensures that only 'admin' users can access admin functionalities.
     * Redirects to login page with an error if unauthorized.
     */
    private function requireAdminAuth()
    {
        if (!$this->session->isLoggedIn() || !$this->session->hasRole('admin')) {
            $this->session->setFlash('error', 'Access denied. You must be an administrator to access this area.', 'error');
            header('Location: /login');
            exit;
        }
    }

    /**
     * Displays the main admin dashboard.
     * Corresponds to the '/admin/dashboard' route (GET request).
     */
    public function dashboard()
    {
        $this->requireAdminAuth();
        require_once BASE_PATH . 'public/views/admin/admin_dashboard.php';
    }

    /**
     * Displays a list of all users and provides options to manage them.
     * Corresponds to the '/admin/users' route (GET request).
     */
    public function manageUsers()
    {
        $this->requireAdminAuth();
        $allUsers = $this->userModel->getAllUsers();

        // The $allUsers variable will be available in the included view
        require_once BASE_PATH . 'public/views/admin/manage_users.php';
    }

    /**
     * Displays the form for editing a specific user's details and role.
     * Corresponds to the '/admin/users/edit/{id}' route (GET request).
     *
     * @param int $userId The ID of the user to edit.
     */
    public function showUserEditForm($userId)
    {
        $this->requireAdminAuth();

        $user = $this->userModel->findUserById($userId);

        if (!$user) {
            $this->session->setFlash('admin_message', 'User not found.', 'error');
            $this->session->setFlash('admin_status', 'error');
            header('Location: /admin/users');
            exit;
        }

        // $user variable will be available in the included view for populating form fields
        // You'll need to create this view: public/views/admin/edit_user.php
        echo "<!DOCTYPE html>
              <html lang='en'>
              <head>
                  <meta charset='UTF-8'>
                  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                  <title>Edit User - Admin - MovieFlix</title>
                  <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
                  <style>
                      body { background-color: #1a1a1a; color: #f0f0f0; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
                      .edit-form-placeholder { background-color: #2a2a2a; padding: 40px; border-radius: 10px; text-align: center; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5); }
                      .edit-form-placeholder h1 { color: #e50914; font-size: 2.5em; margin-bottom: 20px; }
                      .edit-form-placeholder p { font-size: 1.2em; color: #bbb; }
                      .edit-form-placeholder a { color: #17c3ba; text-decoration: none; margin-top: 20px; display: inline-block; }
                      .edit-form-placeholder a:hover { text-decoration: underline; }
                      .form-group { margin-bottom: 15px; text-align: left; }
                      .form-group label { display: block; margin-bottom: 5px; color: #bbb; }
                      .form-group input, .form-group select { width: calc(100% - 20px); padding: 10px; border-radius: 5px; border: 1px solid #444; background-color: #333; color: #f0f0f0; }
                      .form-group button { background-color: #17c3ba; color: white; padding: 10px 20px; border-radius: 5px; border: none; cursor: pointer; margin-top: 20px; }
                      .form-group button:hover { background-color: #14a198; }
                  </style>
              </head>
              <body>
                  <div class='edit-form-placeholder'>
                      <h1>Edit User: " . htmlspecialchars($user['username']) . "</h1>
                      <form action='/admin/users/edit/" . htmlspecialchars($user['id']) . "' method='POST'>
                          <div class='form-group'>
                              <label for='username'>Username:</label>
                              <input type='text' id='username' name='username' value='" . htmlspecialchars($user['username']) . "' required>
                          </div>
                          <div class='form-group'>
                              <label for='email'>Email:</label>
                              <input type='email' id='email' name='email' value='" . htmlspecialchars($user['email']) . "' required>
                          </div>
                          <div class='form-group'>
                              <label for='role'>Role:</label>
                              <select id='role' name='role' required>
                                  <option value='user' " . ($user['role'] == 'user' ? 'selected' : '') . ">User</option>
                                  <option value='uploader' " . ($user['role'] == 'uploader' ? 'selected' : '') . ">Uploader</option>
                                  <option value='admin' " . ($user['role'] == 'admin' ? 'selected' : '') . ">Admin</option>
                              </select>
                          </div>
                          <button type='submit'>Update User</button>
                      </form>
                      <p><a href='/admin/users'>Back to User Management</a></p>
                  </div>
              </body>
              </html>";
        exit; // Exit after displaying the placeholder
    }

    /**
     * Processes the submitted form for editing a user.
     * Corresponds to the '/admin/users/edit/{id}' route (POST request).
     *
     * @param int $userId The ID of the user to update.
     */
    public function processUserEdit($userId)
    {
        $this->requireAdminAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->session->setFlash('admin_message', 'Invalid request method.', 'error');
            $this->session->setFlash('admin_status', 'error');
            header('Location: /admin/users');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = trim($_POST['role'] ?? '');

        // Validate inputs
        if (empty($username) || empty($email) || empty($role) || !in_array($role, ['user', 'uploader', 'admin'])) {
            $this->session->setFlash('admin_message', 'Invalid user data provided.', 'error');
            $this->session->setFlash('admin_status', 'error');
            header('Location: /admin/users/edit/' . $userId);
            exit;
        }

        // Check if username/email are unique if they are changed
        $existingUser = $this->userModel->findUserByIdentifier($username);
        if ($existingUser && $existingUser['id'] != $userId) {
            $this->session->setFlash('admin_message', 'Username already taken by another user.', 'error');
            $this->session->setFlash('admin_status', 'error');
            header('Location: /admin/users/edit/' . $userId);
            exit;
        }
        $existingUser = $this->userModel->findUserByIdentifier($email);
        if ($existingUser && $existingUser['id'] != $userId) {
            $this->session->setFlash('admin_message', 'Email already taken by another user.', 'error');
            $this->session->setFlash('admin_status', 'error');
            header('Location: /admin/users/edit/' . $userId);
            exit;
        }


        if ($this->userModel->updateUser($userId, $username, $email, $role)) {
            $this->session->setFlash('admin_message', 'User updated successfully!', 'success');
            $this->session->setFlash('admin_status', 'success');
        } else {
            $this->session->setFlash('admin_message', 'Failed to update user. Please try again.', 'error');
            $this->session->setFlash('admin_status', 'error');
        }

        header('Location: /admin/users');
        exit;
    }

    /**
     * Deletes a user.
     * Corresponds to the '/admin/users/delete/{id}' route (POST request).
     *
     * @param int $userId The ID of the user to delete.
     */
    public function deleteUser($userId)
    {
        $this->requireAdminAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->session->setFlash('admin_message', 'Invalid request method for deletion.', 'error');
            $this->session->setFlash('admin_status', 'error');
            header('Location: /admin/users');
            exit;
        }

        // Prevent admin from deleting themselves (optional but recommended)
        if ($userId == $this->session->getUserId()) {
            $this->session->setFlash('admin_message', 'You cannot delete your own admin account.', 'error');
            $this->session->setFlash('admin_status', 'error');
            header('Location: /admin/users');
            exit;
        }

        // IMPORTANT: Due to ON DELETE CASCADE on 'movies' table,
        // deleting a user will also delete all movies uploaded by them.
        if ($this->userModel->deleteUser($userId)) {
            $this->session->setFlash('admin_message', 'User deleted successfully!', 'success');
            $this->session->setFlash('admin_status', 'success');
        } else {
            $this->session->setFlash('admin_message', 'Failed to delete user.', 'error');
            $this->session->setFlash('admin_status', 'error');
        }

        header('Location: /admin/users');
        exit;
    }

    /**
     * Displays a list of all movies (regardless of uploader) and provides options to manage them.
     * Corresponds to the '/admin/movies' route (GET request).
     */
    public function manageContent()
    {
        $this->requireAdminAuth();
        $allMovies = $this->movieModel->getAllMovies();

        // The $allMovies variable will be available in the included view
        require_once BASE_PATH . 'public/views/admin/manage_content.php';
    }

    /**
     * Deletes any movie (regardless of uploader).
     * Corresponds to the '/admin/movies/delete/{id}' route (POST request).
     *
     * @param int $movieId The ID of the movie to delete.
     */
    public function deleteContent($movieId)
    {
        $this->requireAdminAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->session->setFlash('admin_message', 'Invalid request method for deletion.', 'error');
            $this->session->setFlash('admin_status', 'error');
            header('Location: /admin/movies');
            exit;
        }

        $movie = $this->movieModel->findMovieById($movieId);
        if (!$movie) {
            $this->session->setFlash('admin_message', 'Movie not found for deletion.', 'error');
            $this->session->setFlash('admin_status', 'error');
            header('Location: /admin/movies');
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
            $this->session->setFlash('admin_message', 'Movie deleted successfully!', 'success');
            $this->session->setFlash('admin_status', 'success');
        } else {
            $this->session->setFlash('admin_message', 'Failed to delete movie from database.', 'error');
            $this->session->setFlash('admin_status', 'error');
        }

        header('Location: /admin/movies');
        exit;
    }

    /**
     * Displays the site settings page.
     * Corresponds to the '/admin/settings' route (GET request).
     */
    public function siteSettings()
    {
        $this->requireAdminAuth();
        // Here you would fetch current site settings from a config table or file
        // and pass them to the view.
        // For now, a placeholder view.
        echo "<!DOCTYPE html>
              <html lang='en'>
              <head>
                  <meta charset='UTF-8'>
                  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                  <title>Site Settings - Admin - MovieFlix</title>
                  <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
                  <style>
                      body { background-color: #1a1a1a; color: #f0f0f0; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
                      .settings-placeholder { background-color: #2a2a2a; padding: 40px; border-radius: 10px; text-align: center; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5); }
                      .settings-placeholder h1 { color: #e50914; font-size: 2.5em; margin-bottom: 20px; }
                      .settings-placeholder p { font-size: 1.2em; color: #bbb; }
                      .settings-placeholder a { color: #17c3ba; text-decoration: none; margin-top: 20px; display: inline-block; }
                      .settings-placeholder a:hover { text-decoration: underline; }
                  </style>
              </head>
              <body>
                  <div class='settings-placeholder'>
                      <h1>Site Settings</h1>
                      <p>This is a placeholder for global website settings.</p>
                      <p>Here, you would implement forms to manage genres, categories, banners, etc.</p>
                      <a href='/admin/dashboard'>Back to Admin Dashboard</a>
                  </div>
              </body>
              </html>";
        exit;
    }
}
