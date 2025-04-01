<?php
require_once '../../Controllers/get_data.php'; // Inclusion du contrôleur pour récupérer les données utilisateur
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - WEB4ALL</title>
    <link rel="stylesheet" href="../../../Public/css/profil.css">
</head>
<body>
    <div class="header">WEB4ALL</div>
    <div class="nav">ACCUEIL | STAGE | ENTREPRISE | PROFIL</div>
    
    <div class="container" id="profile-container">
        <h2 id="profile-title"></h2>
        <div id="dynamic-content">
            <!-- Contenu dynamique chargé ici -->
        </div>
    </div>
    <script src="../../../Public/js/profil.js"></script>
</body>
</html>