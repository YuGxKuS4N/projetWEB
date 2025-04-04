<?php

require_once 'config.php'; // Charger les paramètres de configuration

// Configuration file

// Define database constants
define('DB_HOST', '86.71.46.25');
define('DB_USER', 'user');
define('DB_PASS', 'Php@1234');
define('DB_NAME', 'projet');

class Database {
    private $host = DB_HOST;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $dbname = DB_NAME;
    private $conn;

    public function connect() {
        if ($this->conn == null) {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

            if ($this->conn->connect_error) {
                die("Erreur de connexion à la base de données : " . $this->conn->connect_error);
            }
        }
        return $this->conn;
    }

    public function disconnect() {
        if ($this->conn != null) {
            $this->conn->close();
            $this->conn = null;
        }
    }
}
?>
