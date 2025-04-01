<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - WEB4ALL</title>
    <link rel="stylesheet" href="../../../Public/css/acceuil.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <ul class="nav-left">
                <li><a href="../stage/stage.php">STAGE</a></li>
            </ul>    

            <div class="nav-logo">
                <a href="index.php">
                    <img src="../../../Public/images/logo.png" alt="Logo du Site">
                </a>
            </div>

            <ul class="nav-right">
                <li><a href="../creation_compte/inscription.php">S'INSCRIRE</a></li>
                <li><a href="../creation_compte/connexion.php">CONNEXION</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <video id="background-video" autoplay loop muted class="background-video">
            <source src="../../../Public/videos/bckg.mp4" type="video/mp4">
            Votre navigateur ne supporte pas les vidéos HTML5.
        </video>
        <div class="overlay"></div>
        <div class="hero-content">
            <h1>PRENDS TON <br> FUTUR EN MAIN : <br> CESI TA CHANCE !</h1>
            <a href="../stage/stage.php" class="btn">OFFRES DE STAGE</a>
        </div>
    </section>
    
    <footer>
        <p>&copy; <?php echo date("Y"); ?> WEB4ALL. Tous droits réservés.</p>
    </footer>
</body>
</html>