<?php  
session_start(); // Démarre la session PHP  

// Inclure le fichier de chargement des vues  
include_once dirname(__DIR__) . '/Controllers/c_chargement.php';   
?>  

<!DOCTYPE html>  
<html lang="fr">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>WEB4ALL</title>  
    <link rel="stylesheet" href="../css/style.css">   
</head>  
<body>  
    <header>  
    <nav class="navbar">
        <ul class="nav-left">
            <li><a href="/projetWEB/MODEL-MVC/Views/acceuil/acceuil.php">ACCUEIL</a></li>
            <li><a href="/projetWEB/MODEL-MVC/Views/stage/stage.php">STAGE</a></li>
        </ul>    

        <div class="nav-logo">
            <a href="/projetWEB/MODEL-MVC/Views/acceuil/acceuil.php">
                <img src="/projetWEB/MODEL-MVC/Public/image/logo.png" alt="Logo du Site">
            </a>
        </div>

        <uel class="nav-right">
            <li><a href="/projetWEB/MODEL-MVC/Views/creation_compte/inscription.php">S'INSCRIRE</a></li>
        </ul>
    </nav>

    <div class="form-box">
        <form class="form" action="/projetWEB/MODEL-MVC/Controllers/c_connexion.php" method="POST">
            <span class="title">Se connecter</span>
            <span class="subtitle">Entrez vos identifiants pour accéder à votre compte.</span>
            
            <!-- Affichage des erreurs -->
            <?php if (isset($_GET['error'])): ?>
                <p class="error-message"><?= htmlspecialchars($_GET['error']) ?></p>
            <?php endif; ?>

            <div class="form-container">
                <input type="email" class="input" name="email" placeholder="E-mail" required>
                <input type="password" class="input" name="password" placeholder="Mot de passe" required>
            </div>
            <button type="submit">Connexion</button>
        </form>
        <div class="form-section">
            <p>Pas encore de compte ? <a href="/projetWEB/MODEL-MVC/Views/creation_compte/inscription.php">Inscrivez-vous ici.</a></p>
        </div>
    </div>
    </header>  

    <div class="container" id="profile-container">  
        <h2 id="profile-title">Mon Profil</h2>  
        <div id="dynamic-content">  
            <!-- Contenu dynamique chargé ici -->  
            <?php loadPage($page); ?>  
        </div>  
    </div>  

    <script src="/projetWEB/MODEL-MVC/Public/js/responsive.js"></script>  
</body>  
</html>  