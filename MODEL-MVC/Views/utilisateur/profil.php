<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    error_log("Redirection : utilisateur non connecté."); // Log si l'utilisateur n'est pas connecté
    header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php");
    exit();
}

// Inclure directement c_get_data.php pour récupérer les données utilisateur
ob_start();
include __DIR__ . '/../../Controllers/c_get_data.php';
$data = ob_get_clean();

// Décoder la réponse JSON
$userData = json_decode($data, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    error_log("Erreur JSON : " . json_last_error_msg()); // Log d'erreur JSON
    $userData = ["error" => "Erreur lors du décodage des données utilisateur."];
}

error_log("Données utilisateur après décodage : " . print_r($userData, true)); // Log des données après décodage
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
                    <img src="/projetWEB/MODEL-MVC/Public/image/logo.png" alt="Logo du Site">
                </a>
            </div>
        </nav>
    </header>

    <div class="container" id="profile-container">
        <h2 id="profile-title">Mon Profil</h2>
        <div id="dynamic-content">
            <?php if (isset($userData['error'])): ?>
                <p>Erreur : <?php echo htmlspecialchars($userData['error']); ?></p>
            <?php elseif (!empty($userData)): ?>
                <?php foreach ($userData as $key => $value): ?>
                    <div class="profile-field">
                        <label for="<?php echo htmlspecialchars($key); ?>"><?php echo htmlspecialchars($key); ?></label>
                        <input type="text" id="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>" readonly>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune donnée utilisateur disponible.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>