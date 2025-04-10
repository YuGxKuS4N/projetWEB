<?php
require_once 'Config/Database.php';

$db = new Database();
$conn = $db->connect();

if ($conn) {
    echo "Connexion réussie à la base de données.";
} else {
    echo "Échec de la connexion.";
}
?>