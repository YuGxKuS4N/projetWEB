<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);i_set('display_startup_errors', 1);
error_reporting(E_ALL);(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/projetWEB/MODEL-MVC/Controllers/c_candidature.php'; // Inclusion du contrôleure_once $_SERVER['DOCUMENT_ROOT'] . '/projetWEB/MODEL-MVC/Controllers/c_candidature.php'; // Inclusion du contrôleur
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"> charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">a name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Postuler - WEB4ALL</title>
  <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/postuler.css">href="/projetWEB/MODEL-MVC/Public/css/postuler.css">
</head>
<body>
  <div class="header">WEB4ALL</div>
  <div class="container"> class="container">
    <h2 id="stage-title">Chargement des informations du stage...</h2> du poste</h2>
    <form action="/projetWEB/MODEL-MVC/Controllers/c_candidature.php" method="POST" enctype="multipart/form-data">trollers/c_candidature.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" id="stage-id" name="stage_id" value="1"> <!-- Valeur par défaut pour tester -->
      <div class="form-group">
        <label for="prenom">Prénom</label><div class="form-group">
        <input type="text" id="prenom" name="prenom" required>énom</label>
      </div>m" name="prenom" required>
      <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" required><div class="form-group">
      </div>label>
      <div class="form-group">"nom" required>
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required>
      </div><div class="form-group">
      <div class="form-group">ail</label>
        <label>Importer CV</label>ail" name="email" required>
        <input type="file" id="cv" name="cv" class="upload-btn" required>
      </div>
      <button type="submit">Envoyer la candidature</button><div class="form-group">
    </form>
  </div>put type="file" id="cv" name="cv" class="upload-btn" required>
  <script src="/projetWEB/MODEL-MVC/Public/js/postuler.js"></script>div>
</body>
</html>button type="submit">Envoyer la candidature</button>
  <script src="/projetWEB/MODEL-MVC/Public/js/postuler.js"></script>
</body>
</html>
