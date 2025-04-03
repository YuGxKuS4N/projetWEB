<?php
// Point d'entrée principal pour l'application

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_PATH', dirname(__DIR__, 2));

function loadPage($page) {
    $viewsPath = BASE_PATH . '/Views/';
    $filePath = $viewsPath . $page . '.php';

    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
        http_response_code(404);
        echo "<h1>404 - Page non trouvée</h1>";
    }
}

// Si la requête est pour la racine (`/`), charger directement la vue `acceuil.php`
if ($_SERVER['REQUEST_URI'] === '/') {
    loadPage('acceuil/acceuil'); // Charge la vue `acceuil.php` dans le dossier `Views/acceuil`
    exit;
}

// Récupérer la page demandée dans l'URL (par exemple : ?page=acceuil)
$page = $_GET['page'] ?? 'acceuil';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB4ALL</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php
    // Charger la page demandée uniquement si ce n'est pas la racine
    if ($_SERVER['REQUEST_URI'] !== '/') {
        loadPage($page);
    }
    ?>
 <!-- <script src="../js/responsive.js"></script> -->
</body>
</html>