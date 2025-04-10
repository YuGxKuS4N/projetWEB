<!-- filepath: c:\wamp64\www\projetWEB\MODEL-MVC\Views\acceuil\acceuil.php -->
<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si la session est déjà active avant d'appeler session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
error_log("Cookie de session : " . json_encode($_COOKIE)); // Journal pour vérifier les cookies

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    // Rediriger vers la page de connexion si non connecté
    header("Location: ../creation_compte/connexion.php");
    exit();
}

// Récupérer le rôle de l'utilisateur
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - WEB4ALL</title>
    <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/acceuil.css?v=<?php echo time(); ?>"> <!-- Ajout d'un paramètre pour éviter le cache -->
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-logo">
                <a href="/projetWEB/MODEL-MVC/Views/acceuil/acceuil.php">
                    <img src="/projetWEB/MODEL-MVC/Public/image/logo.png" alt="Logo du Site">
                </a>
            </div>
            <ul class="nav-right">
    <li>
        <a href="/projetWEB/MODEL-MVC/Controllers/c_deconnexion.php?redirect=connexion">DÉCONNEXION</a>
    </li>
</ul>
        </nav>
    </header>
    <section class="hero">
        <video id="background-video" autoplay loop muted class="background-video">
            <source src="/projetWEB/MODEL-MVC/Public/image/bckg.mp4" type="video/mp4"> 
            Votre navigateur ne supporte pas les vidéos HTML5.
        </video>
        <div class="overlay"></div>
        <div class="hero-content">
            <div class="hero-box">
                <h1>
                    <?php
                    // Afficher un titre différent en fonction du rôle
                    if ($role === 'stagiaire') {
                        echo "<span class='stagiaire-title'>PRENDS TON <br> FUTUR EN MAIN : <br> CESI TA CHANCE !</span>";
                    } elseif ($role === 'entreprise') {
                        echo "BIENVENUE DANS <br> VOTRE ESPACE ENTREPRISE";
                    } elseif ($role === 'pilote') {
                        echo "BIENVENUE DANS <br> VOTRE ESPACE PILOTE";
                    } elseif ($role === 'admin') {
                        echo "BIENVENUE DANS <br> L'ESPACE ADMINISTRATION";
                    }
                    ?>
                </h1>
                <?php
                // Afficher un bouton différent en fonction du rôle
                if ($role === 'stagiaire') {
                    echo '<a href="/projetWEB/MODEL-MVC/Views/stage/stage.php" class="btn">OFFRES DE STAGE</a>';
                } elseif ($role === 'entreprise') {
                    echo '<a href="/projetWEB/MODEL-MVC/Views/ajout_stage/ajout.php" class="btn">DÉPOSER UNE OFFRE</a>';
                } elseif ($role === 'pilote') {
                    echo '<a href="/projetWEB/MODEL-MVC/Views/pilote/eleves.php" class="btn">MES ÉLÈVES</a>';
                } elseif ($role === 'admin') {
                    echo '<a href="/projetWEB/MODEL-MVC/Views/admin/admin.php" class="btn">PANEL ADMINISTRATION</a>';
                }
                ?>
                <!-- Bouton pour accéder à la page profil -->
                <a href="/projetWEB/MODEL-MVC/Views/utilisateur/profil.php?user_type=<?php echo htmlspecialchars($_SESSION['role']); ?>&user_id=<?php echo htmlspecialchars($_SESSION['user_id']); ?>" class="btn">MON PROFIL</a>
            </div>
        </div>
    </section>
    
    <footer>

    <div class="footer-bottom">
        <p>Copyright &copy; <?php echo date("Y"); ?> <a href="#">WEB4ALL</a>. Tous droits réservés.</p>
    </div>
</footer>
</body>
</html>