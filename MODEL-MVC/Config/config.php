<?php  

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('session.cookie_secure', 0); 
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.gc_maxlifetime', 3600); 
session_set_cookie_params(3600); 

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