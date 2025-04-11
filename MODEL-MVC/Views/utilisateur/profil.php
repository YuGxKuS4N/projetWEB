<?php  
session_start(); // Démarrer la session  

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {  
    error_log("Redirection : utilisateur non connecté.");  
    header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php");  
    exit();  
}  

// Appel au contrôleur pour récupérer les données utilisateur  
$url = "http://86.71.46.25:200/projetWEB/MODEL-MVC/Controllers/c_get_data.php";  
$ch = curl_init($url);  

// Configurer cURL pour retourner la réponse  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  

// Exécuter la requête cURL  
$data = curl_exec($ch);  
$error = curl_error($ch);  
curl_close($ch);  

if ($data === false) {  
    error_log("Erreur CURL : $error");  
    $userData = ["error" => "Impossible de récupérer les données utilisateur."];  
} else {  
    error_log("Données récupérées via CURL : $data");  
    $userData = json_decode($data, true);  

    // Vérification des erreurs de décodage JSON  
    if (json_last_error() !== JSON_ERROR_NONE) {  
        error_log("Erreur JSON : " . json_last_error_msg());  
        $userData = ["error" => "Erreur lors du décodage des données utilisateur."];  
    }  
}  

// Log des données utilisateur après décodage  
error_log("Données utilisateur après décodage : " . print_r($userData, true));  
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
                        <label for="<?php echo htmlspecialchars($key); ?>"><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $key))); ?></label>  
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