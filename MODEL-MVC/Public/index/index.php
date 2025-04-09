<!-- filepath: c:\wamp64\www\projetWEB\MODEL-MVC\Public\index\index.php -->
<?php  
session_start(); // Démarre la session PHP  

// Inclure le fichier de chargement des vues  
include_once __DIR__ . '/projetWEB/MODEL-MVC/Controllers/c_chargement.php';   
?>  

<!DOCTYPE html>  
<html lang="fr">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>WEB4ALL</title>  

    <!-- Chemins relatifs à partir de projetWEB -->
    <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/style.css">
    <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/footer.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">  
</head>  
<body>  
    <header>  
        <nav class="navbar">
            <div class="nav-logo">
                <a href="/projetWEB/MODEL-MVC/Views/acceuil/acceuil.php">
                    <img src="/projetWEB/MODEL-MVC/Public/image/logo.png" alt="Logo du Site">
                </a>
            </div>
        </nav>
    </header>  

    <div class="container" id="profile-container">  
        <h2 id="profile-title">Mon Profil</h2>  
        <div id="dynamic-content">  
            <!-- Contenu dynamique chargé ici -->  
            <?php
            $page = $_GET['page'] ?? '/projetWEB/MODEL-MVC/Views/acceuil/acceuil.php'; // Page par défaut
            loadPage($page);
            ?>  
        </div>  
    </div>  

    <!-- Inclusion du footer -->
    <?php include __DIR__ . '/projetWEB/MODEL-MVC/Views/footer/footer.php'; ?>

    <script src="/projetWEB/MODEL-MVC/Public/js/responsive.js"></script>
</body>  
</html>
