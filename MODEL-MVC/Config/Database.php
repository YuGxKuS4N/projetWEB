<?php

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Définir les constantes de configuration de la base de données
define('DB_HOST', '86.71.46.25');
define('DB_USER', 'user');
define('DB_PASS', 'Php@1234');
define('DB_NAME', 'projet');
define('DB_PORT', 212); // Port explicite
define('DB_TIMEOUT', 5); // Timeout en secondes

class Database {
    private $host = DB_HOST;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $dbname = DB_NAME;
    private $port = DB_PORT;
    private $conn;

    public function connect() {
        if ($this->conn == null) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Activer les exceptions pour mysqli
            try {
                $this->conn = new mysqli();
                $this->conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, DB_TIMEOUT); // Timeout explicite
                $this->conn->real_connect($this->host, $this->username, $this->password, $this->dbname, $this->port);
            } catch (mysqli_sql_exception $e) {
                error_log("Erreur de connexion à la base de données : " . $e->getMessage());
                die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
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