<?php
// Inclure le fichier autoload de Composer pour charger les classes
require_once __DIR__ . '/../../vendor/autoload.php';  // Utilise un chemin relatif vers le dossier vendor  // Modifie ce chemin si nécessaire

use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\RSA;

// Informations de connexion
$hostname = '86.71.46.25';  
$port = 213;
$username = 'abousalih';
$password = '4243';  


$mysql_hostname = '127.0.0.1'; 
$mysql_port = 3306; 
$mysql_username = 'root';  
$mysql_password = 'projetweb';   
$mysql_dbname = 'projetweb'; 

echo "User: " . $mysql_username . "<br>";
echo "Password: " . $mysql_password . "<br>";
echo "Host: " . $mysql_hostname . "<br>";
echo "Port: " . $mysql_port . "<br>";

// Création de l'objet SSH
$ssh = new SSH2($hostname, $port);

// Connexion au serveur SSH avec le mot de passe
if (!$ssh->login($username, $password)) {
    exit('Échec de la connexion SSH');
}

echo "Connecté avec succès via SSH!";
// Création d'un tunnel SSH pour rediriger le port MySQL local vers le serveur distant
// Connecte-toi à MySQL en utilisant la connexion SSH comme tunnel
$ssh->exec("ssh -L 3306:127.0.0.1:3306 -N &");  // Tunnel SSH pour MySQL

// Connexion à la base de données MySQL via le tunnel SSH
$conn = new mysqli($mysql_hostname, $mysql_username, $mysql_password, $mysql_dbname, port: $mysql_port);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion à la base de données échouée : " . $conn->connect_error);
}

echo "Connexion à la base de données MySQL réussie !\n";


// Fermer la connexion MySQL
//$conn->close();
?>