<?php  
session_start(); // Démarre la session PHP  

// Inclure le fichier de chargement des vues  
include_once dirname(__DIR__) . '/Controllers/c_chargement.php';   
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
            <ul class="nav-left">
                <li><a href="/projetWEB/MODEL-MVC/Views/acceuil/acceuil.php">ACCUEIL</a></li>
                <li><a href="/projetWEB/MODEL-MVC/Views/stage/stage.php">STAGE</a></li>
            </ul>    

            <div class="nav-logo">
                <a href="/projetWEB/MODEL-MVC/Views/acceuil/acceuil.php">
                    <img src="/projetWEB/MODEL-MVC/Public/image/logo.png" alt="Logo du Site">
                </a>
            </div>

            <ul class="nav-right">
                <li><a href="/projetWEB/MODEL-MVC/Views/creation_compte/inscription.php">S'INSCRIRE</a></li>
            </ul>
        </nav>
    </header>  

    <div class="container" id="profile-container">  
        <h2 id="profile-title">Mon Profil</h2>  
        <div id="dynamic-content">  
            <!-- Contenu dynamique chargé ici -->  
            <?php loadPage($page); ?>  
        </div>  
    </div>  

    <!-- Inclure le script responsive.js -->
    <script src="/projetWEB/MODEL-MVC/Public/js/responsive.js"></script>
</body>  
</html>