<?php
$servername = "localhost";
$username = "root"; // Remplace par ton utilisateur MySQL
$password = "projet213"; // Ton mot de passe MySQL
$dbname = "projetweb"; // Remplace par le nom de ta base de données

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}
?>
