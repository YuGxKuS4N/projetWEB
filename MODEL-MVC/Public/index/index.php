<?php
session_start(); // Démarre la session PHP

// BASE_PATH correspond à la racine de votre projet (ici, MODEL-MVC)
define('BASE_PATH', dirname(DIR, 2));

/**
 
Fonction pour charger une vue.
La vue doit se trouver dans le dossier "Views" et sera appelée avec son chemin relatif.
Par exemple, pour charger "Views/acceuil/acceuil.php", on appelle loadPage('acceuil/acceuil')*/
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

// Si le paramètre "page" n'est pas défini ou s'il vaut "acceuil",
// charger directement la page d'accueil "acceuil/acceuil.php"
if (!isset($_GET['page']) || $_GET['page'] === 'acceuil') {
    loadPage('acceuil/acceuil');
    exit();
}

// Sinon, récupérer la page demandée dans l'URL (ex: ?page=admin/admin)
$page = $_GET['page'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB4ALL</title>
    <link rel="stylesheet" href="<?= BASE_PATH ?>/Public/css/style.css">
</head>
<body>
    <?php
    // Charger la page demandée
    loadPage($page);
    ?>
    <script src="<?= BASE_PATH ?>/Public/js/responsive.js"></script>
</body>
</html>