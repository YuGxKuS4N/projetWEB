<?php
// Point d'entrée principal pour l'application

// Démarrer la session
session_start();

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

// Routage simple
switch ($page) {
    case 'acceuil':
        loadPage('acceuil/acceuil');
        break;

    case 'connexion':
        loadPage('creation_compte/connexion');
        break;

    case 'inscription':
        loadPage('creation_compte/inscription');
        break;

    case 'stage':
        loadPage('stage/stage');
        break;

    case 'postuler':
        loadPage('stage/postuler');
        break;

    case 'profil':
        loadPage('utilisateur/profil');
        break;

    default:
        // Si la page demandée n'existe pas
        http_response_code(404);
        echo "<h1>404 - Page non trouvée</h1>";
        break;
}
?>