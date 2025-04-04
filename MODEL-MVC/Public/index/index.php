<?php

session_start(); // Démarre la session PHP

/**
 * Fonction pour charger une vue.
 * La vue doit se trouver dans le dossier "Views" et sera appelée avec son chemin relatif.
 *
 * Par exemple, pour charger "Views/acceuil/acceuil.php", on appelle loadPage('acceuil/acceuil').
 */
function loadPage($page) {
    $viewsPath = 'C:/projetWEB/MODEL-MVC/Views/'; // Dossier Views avec chemin absolu

    // Construit le chemin vers le fichier demandé
    $filePath = $viewsPath . $page . '.php';

    // Debug : Vérifie le chemin du fichier
    echo "Chemin recherché : $filePath<br>";

    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
        http_response_code(404);
        echo "<h1>404 - Page non trouvée</h1>";
    }
}

// Détermine la page par défaut ou via GET
if (!isset($_GET['page']) || $_GET['page'] === 'acceuil') {
    $page = 'acceuil/acceuil'; // Page par défaut
} else {
    $page = $_GET['page'];
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB4ALL</title>
    <!-- Chemin absolu vers le fichier CSS -->
    <link rel="stylesheet" href="C:/projetWEB/MODEL-MVC/Public/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-logo">
                <a href="C:/projetWEB/MODEL-MVC/Views/acceuil/acceuil.php">
                    <!-- Chemin absolu vers le logo -->
                    <img src="C:/projetWEB/MODEL-MVC/Public/image/logo.png" alt="Logo du Site">
                </a>
            </div>
            <ul class="nav-right">
                <li><a href="C:/projetWEB/MODEL-MVC/Views/creation_compte/inscription.php">S'INSCRIRE</a></li>
                <li><a href="C:/projetWEB/MODEL-MVC/Views/creation_compte/connexion.php">CONNEXION</a></li>
            </ul>
        </nav>
    </header>

    <div class="container" id="profile-container">
        <h2 id="profile-title">Mon Profil</h2>
        <div id="dynamic-content">
            <?php
            // Charge la page demandée (ou la page par défaut)
            loadPage($page);
            ?>
        </div>
    </div>

    <!-- Chemin absolu vers le fichier JS -->
    <script src="C:/projetWEB/MODEL-MVC/Public/js/responsive.js"></script>
</body>
</html>
