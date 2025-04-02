<?php
class Database {
private $host = "86.71.46.25";
private $port = "212";
private $dbname = "projet";
private $username = "user";
private $password = "Php@1234";
private $conn;

public function __construct() {
    try {
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4";
        $pdo = new PDO($dsn, $this->username, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        echo "Connexion réussie !";
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

// // Inclure le fichier autoload de Composer pour charger les classes
// require_once __DIR__ . '/../../vendor/autoload.php';  // Utilise un chemin relatif vers le dossier vendor  // Modifie ce chemin si nécessaire

// use phpseclib3\Net\SSH2;
// use phpseclib3\Crypt\RSA;

// // Informations de connexion
// $hostname = '86.71.46.25';  
// $port = 213;
// $username = 'abousalih';
// $password = '4243';  


// $mysql_hostname = '192.168.1.26'; // Adresse du serveur MySQL (localhost si le serveur MySQL est sur le même serveur que le script PHP)
// $mysql_port = 3306; 
// $mysql_username = 'phpmyadmin';  
// $mysql_password = 'Php@1234';   
// $mysql_dbname = 'projet'; 


// // Création de l'objet SSH
// $ssh = new SSH2($hostname, $port);

// // Connexion au serveur SSH avec le mot de passe
// if (!$ssh->login($username, $password)) {
//     exit("Échec de la connexion SSH\n");


// echo "Connecté avec succès via SSH!\n";
// // Création d'un tunnel SSH pour rediriger le port MySQL local vers le serveur distant
// // Connecte-toi à MySQL en utilisant la connexion SSH comme tunnel
// $ssh->exec("ssh -L 3306:localhost:3306 -N &");  // Tunnel SSH pour MySQL

// // Connexion à la base de données MySQL via le tunnel SSH
// $conn = new mysqli($mysql_hostname, $mysql_username, $mysql_password, $mysql_dbname, $mysql_port);

// // Vérifier la connexion
// if ($conn->connect_error) {
//     die("Connexion à la base de données échouée : " . $conn->connect_error);
// }

// echo "Connexion à la base de données MySQL réussie !\n";


// Fermer la connexion MySQL

//_--------------------------------------------------

// class Database {
//     private $host = "127.0.0.1"; // Adresse locale (localhost)
//     private $username = "root"; // Nom d'utilisateur MySQL
//     private $password = "projet213"; // Mot de passe MySQL
//     private $dbname = "projetweb"; // Nom de la base de données
//     private $port = 3306; // Port MySQL
     

//     public function __construct() {
//         // Commande SSH pour établir le tunnel
//         $sshCommand = "ssh -p 213 -L 3306:localhost:3306 abousalih@86.71.46.25";

//         // Exécuter la commande SSH en arrière-plan
//         shell_exec($sshCommand . " > /dev/null 2>&1 &");
//     }

    // Méthode pour établir la connexion à la base de données
    public function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname, $this->port);

        if ($this->conn->connect_error) {
            die(json_encode(["error" => "Connexion échouée : " . $this->conn->connect_error]));
        }

        return $this->conn;
    }

    // Méthode pour récupérer la connexion
    public function getConnection() {
        if (!$this->conn) {
            $this->connect();
        }
        return $this->conn;
    }

    // Méthode pour fermer la connexion
    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}    