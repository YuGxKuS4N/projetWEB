<?php
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

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}
echo "Connexion réussie à la base de données MySQL via SSH!";
?>