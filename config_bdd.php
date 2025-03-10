<?php
$servername = "localhost";
$username = "root"; // Remplace par ton utilisateur MySQL
$password = ""; // Ton mot de passe MySQL
$dbname = "nom_de_ta_base"; // Remplace par le nom de ta base de données

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}
?>
