<?php
// Démarre la session PHP
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB4ALL</title>
    <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-logo">
                <!-- Logo de la page -->
                <img src="/projetWEB/MODEL-MVC/Public/images/logo.png" alt="Logo du Site">
            </div>
            <ul class="nav-right">
                <!-- Lien vers la page d'inscription -->
                <li>S'INSCRIRE</li>
                <!-- Lien vers la page de connexion -->
                <li>CONNEXION</li>
            </ul>
        </nav>
    </header>

    <div class="container" id="profile-container">
        <h2 id="profile-title">Mon Profil</h2>
        <div id="dynamic-content">
            <!-- Contenu dynamique chargé ici -->
        </div>
    </div>

    <!-- <script src="/projetWEB/MODEL-MVC/Public/js/responsive.js"></script> -->
</body>
</html>
