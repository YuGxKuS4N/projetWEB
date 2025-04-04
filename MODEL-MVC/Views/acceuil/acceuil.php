<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si la session est déjà active avant d'appeler session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ob_start(); // Démarre la mise en tampon de sortie
$controllerPath = dirname(__DIR__, 3) . '/MODEL-MVC/Controllers/c_connexion.php';
if (!file_exists($controllerPath)) {
    die("Erreur : Le fichier c_connexion.php est introuvable à l'emplacement : $controllerPath");
}
require_once $controllerPath;
ob_end_clean(); // Vide le tampon pour éviter toute sortie indésirable

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php"); // Rediriger vers la page de connexion si non connecté
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
    <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/accueil.css">
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
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <li><a href="/projetWEB/MODEL-MVC/Views/creation_compte/inscription.php">S'INSCRIRE</a></li>
                    <li><a href="/projetWEB/MODEL-MVC/Views/creation_compte/connexion.php">CONNEXION</a></li>
                <?php else: ?>
                    <li>
                        <a href="/projetWEB/MODEL-MVC/Controllers/c_deconnexion.php?redirect=connexion">DÉCONNEXION</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <section class="hero">
        <video id="background-video" autoplay loop muted class="background-video">
            <source src="/projetWEB/MODEL-MVC/Public/videos/bckg.mp4" type="video/mp4">
            Votre navigateur ne supporte pas les vidéos HTML5.
        </video>
        <div class="overlay"></div>
        <div class="hero-content">
            <h1>
                <?php
                // Afficher un titre différent en fonction du rôle
                if ($role === 'etudiant') {
                    echo "PRENDS TON <br> FUTUR EN MAIN : <br> CESI TA CHANCE !";
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
            if ($role === 'etudiant') {
                echo '<a href="/projetWEB/MODEL-MVC/Views/stage/stage.php" class="btn">OFFRES DE STAGE</a>';
            } elseif ($role === 'entreprise') {
                echo '<a href="/projetWEB/MODEL-MVC/Views/stage/ajout.php" class="btn">DÉPOSER UNE OFFRE</a>';
            } elseif ($role === 'pilote') {
                echo '<a href="/projetWEB/MODEL-MVC/Views/pilote/eleves.php" class="btn">MES ÉLÈVES</a>';
            } elseif ($role === 'admin') {
                echo '<a href="/projetWEB/MODEL-MVC/Views/admin/admin.php" class="btn">PANEL ADMINISTRATION</a>';
            }
            ?>
        </div>
    </section>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> WEB4ALL. Tous droits réservés.</p>
    </footer>
    <script src="/projetWEB/MODEL-MVC/Public/js/notifications.js"></script>
</body>
</html>