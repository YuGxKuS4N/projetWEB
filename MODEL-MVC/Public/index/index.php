<?php
session_start(); // Démarre la session PHP

// Vérifier si l'utilisateur est connecté
$isConnected = isset($_SESSION['user_id']) && isset($_SESSION['role']);

// Vérifier si la déconnexion a été effectuée
$logoutMessage = isset($_GET['logout']) && $_GET['logout'] === 'success' ? "Vous avez été déconnecté avec succès." : null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - WEB4ALL</title>
    <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/index.css">
</head>
<body>
    <div class="container">
        <?php if ($logoutMessage): ?>
            <p class="error-message"><?php echo htmlspecialchars($logoutMessage); ?></p>
        <?php endif; ?>
        <h1>Veuillez vous connecter ou vous inscrire</h1>
        <a href="/projetWEB/MODEL-MVC/Views/creation_compte/connexion.php">Se connecter</a>
        <a href="/projetWEB/MODEL-MVC/Views/creation_compte/inscription.php">S'inscrire</a>
    </div>
</body>
</html>