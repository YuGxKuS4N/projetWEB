<?php
// filepath: c:\wamp64\www\projetWEB\MODEL-MVC\Public\index\index.php

session_start(); // Démarre la session PHP

// Vérifier si l'utilisateur est connecté
$isConnected = isset($_SESSION['user_id']) && isset($_SESSION['role']);

// Déterminer la page à charger
$page = $isConnected ? 'acceuil/acceuil.php' : 'creation_compte/connexion.php';

// Utiliser un switch-case pour gérer les redirections
switch ($page) {
    case 'acceuil/acceuil.php':
        $pageTitle = "Accueil - WEB4ALL";
        break;

    case 'creation_compte/connexion.php':
        $pageTitle = "Connexion - WEB4ALL";
        break;

    default:
        $pageTitle = "Erreur - WEB4ALL";
        $page = 'erreur/404.php'; // Page d'erreur par défaut
        break;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1<?php
// filepath: c:\wamp64\www\projetWEB\MODEL-MVC\Public\index\index.php

session_start(); // Démarre la session PHP

// Vérifier si l'utilisateur est connecté
$isConnected = isset($_SESSION['user_id']) && isset($_SESSION['role']);

// Déterminer la page à charger
$page = $isConnected ? 'acceuil/acceuil.php' : 'creation_compte/connexion.php';

// Utiliser un switch-case pour gérer les redirections
switch ($page) {
    case 'acceuil/acceuil.php':
        $pageTitle = "Accueil - WEB4ALL";
        break;

    case 'creation_compte/connexion.php':
        $pageTitle = "Connexion - WEB4ALL";
        break;

    default:
        $pageTitle = "Erreur - WEB4ALL";
        $page = 'erreur/404.php'; // Page d'erreur par défaut
        break;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1