<?php
/**
 * Database.php
 *
 * This class handles the database connection using PDO (PHP Data Objects).
 * It provides methods for connecting, querying, and managing transactions.
 */

class Database
{
    /**
     * @var PDO The PDO database connection object.
     */
    private $pdo;

    /**
     * @var PDOStatement The last executed PDO statement.
     */
    private $stmt;

    /**
     * Constructor: Establishes a database connection when a Database object is created.
     *
     * @param string $host The database host (e.g., 'localhost').
     * @param string $db_name The name of the database.
     * @param string $username The database username.
     * @param string $password The database password.
     */
    public function __construct($host, $db_name, $username, $password)
    {
        $dsn = "mysql:host={$host};dbname={$db_name};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // Default fetch mode to associative array
            PDO::ATTR_EMULATE_PREPARES   => false,                // Disable emulation for better security and performance
        ];

        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
            // echo "Database connected successfully!<br>"; // For testing, remove in production
        } catch (PDOException $e) {
            // Log the error (e.g., to a file, not directly output in production)
            error_log("Database connection failed: " . $e->getMessage(), 0);
            // Display a user-friendly error message
            die("Database connection failed. Please try again later.");
        }
    }

    /**
     * Prepares a SQL query.
     *
     * @param string $sql The SQL query string.
     * @return Database Returns the current Database instance for method chaining.
     */
    public function query($sql)
    {
        $this->stmt = $this->pdo->prepare($sql);
        return $this;
    }

    /**
     * Binds a value to a corresponding placeholder in the SQL query.
     *
     * @param string $param The placeholder name (e.g., ':username').
     * @param mixed $value The value to bind.
     * @param int $type The data type (optional, defaults to PDO::PARAM_STR).
     * @return Database Returns the current Database instance for method chaining.
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
        return $this;
    }

    /**
     * Executes the prepared statement.
     *
     * @return bool True on success, false on failure.
     */
    public function execute()
    {
        return $this->stmt->execute();
    }

    /**
     * Fetches a single row as an associative array.
     *
     * @return array|false The fetched row or false if no row is found.
     */
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Fetches all rows as an associative array.
     *
     * @return array An array of fetched rows.
     */
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Gets the number of affected rows after an INSERT, UPDATE, or DELETE statement.
     *
     * @return int The number of affected rows.
     */
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    /**
     * Returns the ID of the last inserted row or sequence value.
     *
     * @return string The ID of the last inserted row.
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Begins a transaction.
     *
     * @return bool True on success, false on failure.
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commits a transaction.
     *
     * @return bool True on success, false on failure.
     */
    public function commit()
    {
        return $this->pdo->commit();
    }

    /**
     * Rolls back a transaction.
     *
     * @return bool True on success, false on failure.
     */
    public function rollBack()
    {
        return $this->pdo->rollBack();
    }
}
