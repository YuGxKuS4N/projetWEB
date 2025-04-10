<?php
require_once dirname(__DIR__, 3) . '/MODEL-MVC/Controllers/c_get_data.php';

session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    error_log("Session invalide : " . json_encode($_SESSION));
    header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php");
    exit();
}

$userId = $_SESSION['user_id'];
$userType = $_SESSION['role'];

error_log("Paramètres transmis au contrôleur : type=$userType, user_id=$userId");

// ✅ Appel HTTP via cURL
$url = "http://localhost/projetWEB/MODEL-MVC/Controllers/c_get_data.php?user_type=$userType&user_id=$userId&context=profile";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($ch);
curl_close($ch);

if ($data === false) {
    error_log("Erreur lors de la récupération des données utilisateur : type=$userType, user_id=$userId");
    $userData = ["error" => "Impossible de récupérer les données utilisateur."];
} else {
    error_log("Données utilisateur récupérées : " . $data);
    $userData = json_decode($data, true);
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
    <script src="/projetWEB/MODEL-MVC/Public/js/profil.js"></script>
</body>
</html>
<?php
