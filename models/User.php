<?php
/**
 * User.php
 *
 * This model represents the 'users' table in the database.
 * It provides methods for user-related operations like creation, retrieval,
 * password verification, and role management.
 */

class User
{
    /**
     * @var Database The database connection instance.
     */
    private $db;

    /**
     * Constructor: Initializes the User model with a database instance.
     *
     * @param Database $db The database connection object.
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * Finds a user by their username or email.
     *
     * @param string $identifier The username or email.
     * @return array|false An associative array of user data if found, false otherwise.
     */
    public function findUserByIdentifier($identifier)
    {
        $this->db->query('SELECT * FROM users WHERE username = :identifier OR email = :identifier');
        $this->db->bind(':identifier', $identifier);
        return $this->db->single();
    }

    /**
     * Finds a user by their ID.
     *
     * @param int $id The user ID.
     * @return array|false An associative array of user data if found, false otherwise.
     */
    public function findUserById($id)
    {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Creates a new user in the database.
     *
     * @param string $username The user's chosen username.
     * @param string $email The user's email address.
     * @param string $password The user's plain-text password.
     * @param string $role The user's role (e.g., 'user', 'uploader', 'admin').
     * @return bool True on successful creation, false otherwise.
     */
    public function createUser($username, $email, $password, $role = DEFAULT_USER_ROLE)
    {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $this->db->query('INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)');
        $this->db->bind(':username', $username);
        $this->db->bind(':email', $email);
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':role', $role);

        return $this->db->execute();
    }

    /**
     * Verifies a user's password against the hashed password in the database.
     *
     * @param string $inputPassword The plain-text password provided by the user.
     * @param string $hashedPassword The hashed password retrieved from the database.
     * @return bool True if passwords match, false otherwise.
     */
    public function verifyPassword($inputPassword, $hashedPassword)
    {
        return password_verify($inputPassword, $hashedPassword);
    }

    /**
     * Updates a user's information.
     *
     * @param int $id The ID of the user to update.
     * @param string $username The new username.
     * @param string $email The new email.
     * @param string $role The new role.
     * @return bool True on successful update, false otherwise.
     */
    public function updateUser($id, $username, $email, $role)
    {
        $this->db->query('UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id');
        $this->db->bind(':username', $username);
        $this->db->bind(':email', $email);
        $this->db->bind(':role', $role);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Deletes a user from the database.
     *
     * @param int $id The ID of the user to delete.
     * @return bool True on successful deletion, false otherwise.
     */
    public function deleteUser($id)
    {
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Gets all users from the database.
     * @return array An array of all users.
     */
    public function getAllUsers()
    {
        $this->db->query('SELECT id, username, email, role FROM users'); // Exclude password hash
        return $this->db->resultSet();
    }
}
