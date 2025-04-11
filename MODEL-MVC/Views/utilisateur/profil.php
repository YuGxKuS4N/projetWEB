<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    error_log("Redirection : utilisateur non connecté."); // Log si l'utilisateur n'est pas connecté
    header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php");
    exit();
}

// Appel au contrôleur pour récupérer les données utilisateur
$url = "http://86.71.46.25:200/projetWEB/MODEL-MVC/Controllers/c_get_data.php";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true); // Inclure les en-têtes dans la réponse
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    error_log("Erreur HTTP : $httpCode"); // Log de l'erreur HTTP
    $userData = ["error" => "Erreur lors de la récupération des données utilisateur (HTTP $httpCode)."];
} else {
    $data = substr($response, strpos($response, "\r\n\r\n") + 4); // Extraire le corps de la réponse
    $userData = json_decode($data, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Erreur JSON : " . json_last_error_msg()); // Log d'erreur JSON
        $userData = ["error" => "Erreur lors du décodage des données utilisateur."];
    }
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

    <!-- Ajout d'un script pour afficher les logs dans la console -->
    <script>
        // Affiche la réponse brute dans la console
        console.log("Réponse brute de c_get_data.php :", <?php echo json_encode($data); ?>);
    </script>
</body>
</html>