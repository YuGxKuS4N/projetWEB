<!-- filepath: c:\wamp64\www\projetWEB\MODEL-MVC\Public\index\index.php -->
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
    <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/footer.css"> <!-- Inclusion du CSS du footer -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Font Awesome -->
</head>  
<body>  
    <header>  
        <nav class="navbar">
            <div class="nav-logo">
                <a href="../../Views/acceuil/acceuil.php"></a>
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
            $page = $_GET['page'] ?? 'acceuil/acceuil'; // Page par défaut
            loadPage($page);
            ?>  
        </div>  
    </div>  

    <!-- Inclusion du footer -->
    <?php include dirname(__DIR__) . '/../Views/footer/footer.php'; ?>

    <script src="/projetWEB/MODEL-MVC/Public/js/responsive.js"></script>  
</body>  
</html>