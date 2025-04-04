<?php
require_once '/projetWEB/MODEL-MVC/Controllers/c_get_data.php'; // Inclusion du contrôleur pour récupérer les données utilisateur
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - WEB4ALL</title>
    <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/profil.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-logo">
                <a href="/projetWEB/MODEL-MVC/Views/acceuil/acceuil.php">
                    <img src="/projetWEB/MODEL-MVC/Public/images/logo.png" alt="Logo du Site">
                </a>
            </div>
            <ul class="nav-right">
                <li><a href="/projetWEB/MODEL-MVC/Views/creation_compte/inscription.php">S'INSCRIRE</a></li>
                <li><a href="/projetWEB/MODEL-MVC/Views/creation_compte/connexion.php">CONNEXION</a></li>
            </ul>
        </nav>
    </header>

    <div class="container" id="profile-container">
        <h2 id="profile-title">Mon Profil</h2>
        <div id="dynamic-content">
            <!-- Contenu dynamique chargé ici -->
        </div>
    </div>
    <script src="/projetWEB/MODEL-MVC/Public/js/profil.js"></script>
</body>
</html>
