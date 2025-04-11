<?php

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    error_log("Redirection : utilisateur non connecté.");
    header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php");
    exit();
}
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si la session est déjà active avant d'appeler session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    // Rediriger vers la page de connexion si non connecté
    header("Location: ../creation_compte/connexion.php");
    exit();
}
// Appel au contrôleur pour récupérer les données utilisateur
$url = "http://86.71.46.25:200/projetWEB/MODEL-MVC/Controllers/c_get_data.php";
$ch = curl_init($url);

// Inclure les cookies de session dans l'appel CURL
$cookieFile = tempnam(sys_get_temp_dir(), 'CURLCOOKIE');
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

// Supprimer le fichier temporaire des cookies
unlink($cookieFile);

if ($data === false) {
    error_log("Erreur CURL : $error");
    $userData = ["error" => "Impossible de récupérer les données utilisateur. Erreur CURL : $error"];
} else {
    error_log("Données récupérées via CURL : $data");
    $userData = json_decode($data, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Erreur JSON : " . json_last_error_msg());
        $userData = ["error" => "Erreur lors du décodage des données utilisateur."];
    }
}
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