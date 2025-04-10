<?php  
session_start(); // Démarre la session PHP  

// Vérifiez si la session a expiré (par exemple, après 30 minutes d'inactivité)
$timeout = 1800; // 30 minutes
$maxSessionDuration = 3600; // 1 heure (durée maximale de la session)

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    error_log("Session expirée pour l'utilisateur : " . json_encode($_SESSION)); // Journal pour déboguer
    session_unset();
    session_destroy();
    header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php?error=session_expired");
    exit();
}

if (isset($_SESSION['session_start_time']) && (time() - $_SESSION['session_start_time'] > $maxSessionDuration)) {
    error_log("Durée maximale de session atteinte pour l'utilisateur : " . json_encode($_SESSION)); // Journal pour déboguer
    session_unset();
    session_destroy();
    header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php?error=session_timeout");
    exit();
}

$_SESSION['last_activity'] = time(); // Réinitialisez l'horodatage

// BASE_PATH correspond à la racine de votre projet  
define('BASE_PATH', dirname(__DIR__, 1)); // Ajusté pour être à la racine du projet  

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
if (!in_array($page . '.php', $viewPaths)) {  
    http_response_code(404);  
    echo "<h1>404 - Page non trouvée</h1>";  
    exit();  
}  

// Chargement de la page demandée  
loadPage($page);  
?>