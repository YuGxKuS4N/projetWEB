<?php  

// Configuration de la base de données
$mysql_hostname = "86.71.46.25";  
$mysql_port = 212;  
$mysql_username = "user";  
$mysql_password = "Php@1234";  
$mysql_dbname = "projet";  

// Fonction pour établir une connexion à la base de données
function getDatabaseConnection() {
    global $mysql_hostname, $mysql_port, $mysql_username, $mysql_password, $mysql_dbname;

    $conn = new mysqli($mysql_hostname, $mysql_username, $mysql_password, $mysql_dbname, $mysql_port);

    // Vérifier la connexion
    if ($conn->connect_error) {  
        die("Connexion à la base de données échouée : " . $conn->connect_error);  
    }

    return $conn;
}
?>