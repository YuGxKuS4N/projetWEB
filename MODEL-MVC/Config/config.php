<?php  

$mysql_hostname = "86.71.46.25";  
$mysql_port = 212;  
$mysql_username = "user";  
$mysql_password = "Php@1234";  
$mysql_dbname = "projet";  

$conn = new mysqli($mysql_hostname, $mysql_username, $mysql_password, $mysql_dbname, $mysql_port);  

// Vérifier la connexion  
if ($conn->connect_error) {  
    die("Connexion à la base de données échouée : " . $conn->connect_error);  
}  

echo "Connexion à la base de données MySQL réussie !\n";

$conn->close();