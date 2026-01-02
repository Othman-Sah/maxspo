<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;

    public $conn;

    public function __construct() {
        $this->host = defined('DB_HOST') ? DB_HOST : "localhost";
        $this->db_name = defined('DB_NAME') ? DB_NAME : "needsport_pro";
        $this->username = defined('DB_USER') ? DB_USER : "root";
        $this->password = defined('DB_PASS') ? DB_PASS : "root";
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";port=" . (defined('DB_PORT') ? DB_PORT : 3306), $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            die("Database Connection Error: " . $exception->getMessage());
        }
        return $this->conn;
    }
}
?>