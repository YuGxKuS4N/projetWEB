<?php
// Point d'entrée principal pour l'application

// Démarrer la session (appel unique ici)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Définir le chemin de base pour inclure les fichiers
define('BASE_PATH', dirname(__DIR__, 2));

// Fonction pour charger les pages
function loadPage($page) {
    $viewsPath = BASE_PATH . '/Views/';
    $filePath = $viewsPath . $page . '.php';

    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
        // Page non trouvée
        http_response_code(404);
        echo "<h1>404 - Page non trouvée</h1>";
    }
}

// Récupérer la page demandée dans l'URL (par exemple : ?page=acceuil)
$page = $_GET['page'] ?? 'acceuil'; // Par défaut, charger la page d'accueil
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB4ALL</title>
    <link rel="stylesheet" href="/css/style.css"> <!-- Chemin corrigé -->
</head>
<body>
    <?php
    // Charger la page demandée
    loadPage($page);
    ?>
    <script src="/js/responsive.js"></script> <!-- Chemin corrigé -->
</body>
</html>