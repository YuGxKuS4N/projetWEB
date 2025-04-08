<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: /projetWEB/MODEL-MVC/Views/connexion/connexion.php");
    exit();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/projetWEB/MODEL-MVC/Controllers/c_candidature.php'; // Inclusion du contrôleur
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Postuler - WEB4ALL</title>
  <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/postuler.css">
</head>
<body>
  <header>
    <div class="logo">WEB4ALL</div>
    <nav>
      <a href="/projetWEB/MODEL-MVC/Views/acceuil/acceuil.php">Accueil</a>
      <a href="/projetWEB/MODEL-MVC/Views/stage/stage.php">Stages</a>
      <a href="/projetWEB/MODEL-MVC/Views/connexion/deconnexion.php">Déconnexion</a>
    </nav>
  </header>

  <div class="container">
    <h2 id="stage-title">Postuler pour un stage</h2>
    <form action="/projetWEB/MODEL-MVC/Controllers/c_candidature.php" method="POST" enctype="multipart/form-data" accept-charset="UTF-8">
      <input type="hidden" id="stage-id" name="stage_id" value="<?php echo htmlspecialchars($_GET['stage_id'] ?? ''); ?>"> <!-- Récupérer l'ID du stage -->
      <div class="form-group">
        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($_SESSION['prenom'] ?? ''); ?>" required>
      </div>
      <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($_SESSION['nom'] ?? ''); ?>" required>
      </div>
      <div class="form-group">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" required>
      </div>
      <div class="form-group">
        <label>Importer CV</label>
        <input type="file" id="cv" name="cv" class="upload-btn" accept=".pdf" required>
      </div>
      <div class="form-group">
        <label>Importer Lettre de Motivation (optionnel)</label>
        <input type="file" id="motivation" name="motivation" class="upload-btn" accept=".pdf">
      </div>
      <button type="submit">Envoyer la candidature</button>
    </form>
  </div>
</body>
</html>