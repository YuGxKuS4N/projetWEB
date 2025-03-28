<?php
<<<<<<< HEAD
// Paramètres de connexion SSH
$sshHost = "86.71.46.25";
$sshPort = 213;
$sshUser = "abousalih";
$sshPassword = "4243"; 

// Paramètres de connexion MySQL
$mysqlHost = "127.0.0.1";
$mysqlPort = 3306;
$mysqlUser = "root"; 
$mysqlPassword = "projet213"; /
$dbname = "projetweb"; // Le nom de ta base de données

// Création d'une connexion SSH avec mot de passe
$connection = ssh2_connect($sshHost, $sshPort);
if (!ssh2_auth_password($connection, $sshUser, $sshPassword)) {
    die('Échec de l\'authentification SSH avec mot de passe');
}

// Redirection du port MySQL local via SSH
$forwardedPort = 3306;
if (!ssh2_tunnel($connection, $forwardedPort, '127.0.0.1', 3306)) {
    die('Échec de la redirection du port MySQL');
}

// Connexion MySQL via le tunnel SSH
$conn = new mysqli($mysqlHost, $mysqlUser, $mysqlPassword, $dbname, $mysqlPort);
=======
class Database {
    private $host = "127.0.0.1"; // Adresse locale (localhost)
    private $username = "root"; // Nom d'utilisateur MySQL
    private $password = "projet213"; // Mot de passe MySQL
    private $dbname = "projetweb"; // Nom de la base de données
    private $port = 8080; // Port local redirigé via SSH
    private $conn;

    public function __construct() {
        // Commande SSH pour établir le tunnel
        $sshCommand = "ssh -p 213 -L 8080:localhost:80 abousalih@86.71.46.25";
>>>>>>> c9a394c (ajout bdd avec le php en cours de mis en place)

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
<<<<<<< HEAD
echo "Connexion réussie à la base de données MySQL via SSH!";
=======
>>>>>>> c9a394c (ajout bdd avec le php en cours de mis en place)
?>