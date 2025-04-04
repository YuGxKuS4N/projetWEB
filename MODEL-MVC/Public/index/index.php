<?php  
session_start(); // Démarre la session PHP  

// BASE_PATH correspond à la racine de votre projet  
define('BASE_PATH', dirname(__DIR__, 2));  

// Fonction pour scanner les répertoires de vues et retourner les chemins des fichiers  
function getViewPaths($directory) {  
    $files = [];  
    $items = scandir($directory);  
    foreach ($items as $item) {  
        if ($item === '.' || $item === '..') {  
            continue; // Ignorer les répertoires spéciaux  
        }  
        $path = $directory . '/' . $item;  
        if (is_dir($path)) {  
            // Scanner le sous-dossier  
            $subFiles = getViewPaths($path);   
            $files = array_merge($files, $subFiles);  
        } else {  
            // Ajouter le fichier s'il s'agit d'un fichier .php  
            if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {  
                $files[] = str_replace(BASE_PATH . '/Views/', '', $path); // Chemin relatif  
            }  
        }  
    }  
    return $files;  
}  

// Récupère tous les chemins des vues  
$viewPaths = getViewPaths(BASE_PATH . '/Views');  

// Fonction pour charger une vue  
function loadPage($page) {  
    $viewsPath = BASE_PATH . '/Views/';  
    $filePath = $viewsPath . $page . '.php';  

    // Vérifie si le fichier existe avant de le charger  
    if (file_exists($filePath)) {  
        require_once $filePath;  
    } else {  
        http_response_code(404);  
        echo "<h1>404 - Page non trouvée</h1>";  
    }  
}  

// Si le paramètre "page" n'est pas défini ou s'il vaut "acceuil", charger la page d'accueil  
$page = isset($_GET['page']) ? $_GET['page'] : 'acceuil/acceuil';  

// Si la page demandée n'est pas dans les chemins validés, charger une 404  
if (in_array($page . '.php', $viewPaths)) {  
    loadPage($page);  
} else {  
    http_response_code(404);  
    echo "<h1>404 - Page non trouvée</h1>";  
    exit();  
}  
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
    <header>  
        <nav class="navbar">  
            <div class="nav-logo">  
                <a href="<?= BASE_PATH ?>/Views/acceuil/acceuil.php">  
                    <img src="<?= BASE_PATH ?>/Public/images/logo.png" alt="Logo du Site">  
                </a>  
            </div>  
            <ul class="nav-right">  
                <li><a href="<?= BASE_PATH ?>/Views/creation_compte/inscription.php">S'INSCRIRE</a></li>  
                <li><a href="<?= BASE_PATH ?>/Views/creation_compte/connexion.php">CONNEXION</a></li>  
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

    <script src="<?= BASE_PATH ?>/Public/js/responsive.js"></script>  
</body>  
</html>  