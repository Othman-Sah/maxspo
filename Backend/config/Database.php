<?php
/**
 * Database Connection Class
 * Handles all database operations using MySQLi
 */

class Database {
    private $connection;
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $db = DB_NAME;
    private $port = DB_PORT;

    public function connect() {
        $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->db, $this->port);

        if ($this->connection->connect_error) {
            throw new Exception('Database connection failed: ' . $this->connection->connect_error);
        }

        $this->connection->set_charset('utf8mb4');
        return $this->connection;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function query($sql) {
        $result = $this->connection->query($sql);
        if (!$result) {
            throw new Exception('Query failed: ' . $this->connection->error);
        }
        return $result;
    }

    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }

    public function lastInsertId() {
        return $this->connection->insert_id;
    }

    public function affectedRows() {
        return $this->connection->affected_rows;
    }

    public function escape($value) {
        return $this->connection->real_escape_string($value);
    }

    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
?>
