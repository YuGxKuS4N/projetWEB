<?php
// Inclure le fichier autoload de Composer pour charger les classes
require_once __DIR__ . '/../../vendor/autoload.php';  // Utilise un chemin relatif vers le dossier vendor  // Modifie ce chemin si nécessaire

use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\RSA;

// Informations de connexion
$hostname = '86.71.46.25';  // Adresse de ton serveur SSH
$port = 213;  // Port SSH
$username = 'abousalih';  // Ton nom d'utilisateur SSH
$password = '4243';  // Remplace par ton mot de passe SSH

// Informations de connexion MySQL
$mysql_hostname = '127.0.0.1';  // Adresse de la base MySQL
$mysql_port = 3306;  // Port MySQL
$mysql_username = 'root';  // Ton nom d'utilisateur MySQL
$mysql_password = 'projet213';  // Ton mot de passe MySQL
$mysql_dbname = 'projetweb';  // Le nom de ta base de données

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
$conn = new mysqli($mysql_hostname, $mysql_username, $mysql_password, $mysql_dbname, $mysql_port);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion à la base de données échouée : " . $conn->connect_error);
}

echo "Connexion à la base de données MySQL réussie !\n";

// Exemple de requête MySQL (facultatif)
$query = "SELECT * FROM ta_table";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Afficher les résultats de la requête
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Nom: " . $row["nom"]. " - Email: " . $row["email"]. "\n";
    }
} else {
    echo "Aucun résultat trouvé.\n";
}

// Fermer la connexion MySQL
$conn->close();
?>