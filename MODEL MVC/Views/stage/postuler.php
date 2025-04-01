<?php
require_once '../../Controllers/candidature.php'; // Inclusion du contrôleur pour gérer les candidatures
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Postuler - WEB4ALL</title>
  <link rel="stylesheet" href="../../../Public/css/postuler.css">
</head>
<body>
  <div class="header">WEB4ALL</div>
  <div class="container">
    <h2 id="stage-title">Titre du poste</h2>
    <form action="../../../Controllers/candidature.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" id="stage-id" name="stage_id">
      <div class="form-group">
        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" required>
      </div>
      <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" required>
      </div>
      <div class="form-group">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label>Importer CV</label>
        <input type="file" id="cv" name="cv" class="upload-btn" required>
      </div>
      <button type="submit">Envoyer la candidature</button>
    </form>
  </div>
  <script src="../../../Public/js/postuler.js"></script>
</body>
</html>