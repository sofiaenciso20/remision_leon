<?php
// config/database.php
class Database {
    private $host = 'localhost';
    private $db_name = 'remisiones';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                                $this->username, $this->password, array(
                                    PDO::ATTR_PERSISTENT => true,
                                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                                    PDO::ATTR_TIMEOUT => 30,
                                    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
                                ));
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // Log the error to a file for debugging purposes
            error_log("Database Connection Error: " . $exception->getMessage() . "\n", 3, "/tmp/db_errors.log");
            // Throw the exception to be caught by the calling script
            throw $exception;
        }
        return $this->conn;
    }
}
?>
