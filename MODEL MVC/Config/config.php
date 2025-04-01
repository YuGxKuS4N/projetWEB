<?php
class Database {
    private $host = "127.0.0.1"; // Adresse locale (localhost)
    private $username = "root"; // Nom d'utilisateur MySQL
    private $password = "projet213"; // Mot de passe MySQL
    private $dbname = "projetweb"; // Nom de la base de données
    private $port = 3306; // Port MySQL
    private $conn;

    public function __construct() {
        // Commande SSH pour établir le tunnel
        $sshCommand = "ssh -p 213 -L 3306:localhost:3306 abousalih@86.71.46.25";

        // Exécuter la commande SSH en arrière-plan
        shell_exec($sshCommand . " > /dev/null 2>&1 &");
    }

    // Méthode pour établir la connexion à la base de données
    public function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname, $this->port);

        if ($this->conn->connect_error) {
            die(json_encode(["error" => "Connexion échouée : " . $this->conn->connect_error]));
        }

        return $this->conn;
    }
}
?>