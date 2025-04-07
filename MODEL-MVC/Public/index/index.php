<?php  
session_start(); // Démarre la session PHP  

// Inclure le fichier de chargement des vues  
include_once dirname(__DIR__) . '/Controllers/c_chargement.php';   

// Vérifier si la variable $page est définie
$page = $_GET['page'] ?? 'acceuil/acceuil'; // Charger la page d'accueil par défaut
?>  

<!DOCTYPE html>  
<html lang="fr">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>WEB4ALL</title>  
    <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/style.css">  
</head>  
<body>  
    <header>  
        <nav class="navbar">  
            <div class="nav-logo">  
                <a href="/projetWEB/MODEL-MVC/Public/index.php">  
                     
                </a>  
            </div>  
            <ul class="nav-right">  
                <li><a href="/projetWEB/MODEL-MVC/Views/creation_compte/inscription.php">S'INSCRIRE</a></li>  
                <li><a href="/projetWEB/MODEL-MVC/Views/creation_compte/connexion.php">CONNEXION</a></li>  
            </ul>  
        </nav>  
    </header>  

    <div class="container" id="profile-container">  
        <h2 id="profile-title">Mon Profil</h2>  
        <div id="dynamic-content">  
            <!-- Contenu dynamique chargé ici -->  
            <?php 
            try {
                loadPage($page); 
            } catch (Exception $e) {
                echo "<p>Erreur lors du chargement de la page : " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>  
        </div>  
    </div>  

    <script src="/projetWEB/MODEL-MVC/Public/js/responsive.js"></script>  
</body>  
</html>