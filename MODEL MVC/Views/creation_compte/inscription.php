<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inscription - WEB4ALL</title>
  <link rel="stylesheet" href="../../../Public/css/inscription.css" />
</head>
<body>
  <nav class="navbar">
    <ul class="nav-left">
      <li><a href="../accueil/index.php">ACCUEIL</a></li>
      <li><a href="../stage/stage.php">STAGE</a></li>
    </ul>
    <div class="nav-logo">
      <a href="../accueil/index.php">
        <img src="../../../Public/images/logo.png" alt="Logo du Site" />
      </a>
    </div>
    <ul class="nav-right">
      <li><a href="connexion.php">CONNEXION</a></li>
    </ul>
  </nav>

  <div class="form-box">
    <form class="form" action="../../../Back/auth/inscription.php" method="POST">
      <span class="title">Créer un compte</span>
      <div class="form-container">
        <input type="text" class="input" name="prenom" placeholder="Prénom" required />
        <input type="text" class="input" name="nom" placeholder="Nom" required />
        <input type="email" class="input" name="email" placeholder="E-mail" required />
        <input type="password" class="input" name="password" placeholder="Mot de passe" required />
        <input type="password" class="input" name="confirm_password" placeholder="Confirmez le mot de passe" required />
      </div>
      <button type="submit">S'inscrire</button>
    </form>
  </div>
</body>
</html>