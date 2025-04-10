<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stages - WEB4ALL</title>
  <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/stage.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
  <header>
    <div class="logo">WEB4ALL</div>
    <nav>
      <a href="/projetWEB/MODEL-MVC/Views/acceuil/acceuil.php">Accueil</a>
      <a href="/projetWEB/MODEL-MVC/Views/creation_compte/entreprise.php">Entreprises</a>
    </nav>
  </header>

  <main>
    <section class="offers">
      <h2>Nos Offres de Stage</h2>
      <div id="offers-container" class="offers-list">
        <?php if (!empty($offres)): ?>
          <?php foreach ($offres as $offre): ?>
            <div class="offer">
              <h3><?php echo htmlspecialchars($offre['titre']); ?></h3>
              <p><?php echo htmlspecialchars($offre['description']); ?></p>
              <p><strong>Secteur :</strong> <?php echo htmlspecialchars($offre['secteur_activite']); ?></p>
              <p><strong>Date de début :</strong> <?php echo htmlspecialchars($offre['date_debut']); ?></p>
              <p><strong>Durée :</strong> <?php echo htmlspecialchars($offre['duree']); ?> mois</p>
              <p><strong>Lieu :</strong> <?php echo htmlspecialchars($offre['lieu']); ?></p>
              <p>
                <button class="postuler-btn" data-stage-id="<?php echo htmlspecialchars((int)$offre['id_offre']); ?>" data-stage-title="<?php echo htmlspecialchars($offre['titre']); ?>">
                  Postuler
                </button>
              </p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Aucune offre de stage disponible pour le moment.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <!-- Fenêtre modale -->
  <div id="modal" class="modal">
    <div class="modal-content">
      <span class="close-btn">&times;</span>
      <h2 id="modal-title">Postuler pour le stage</h2>
      <form id="postuler-form" action="/projetWEB/MODEL-MVC/Controllers/c_candidature.php" method="POST" enctype="multipart/form-data">
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
          <label for="cv">Importer CV</label>
          <input type="file" id="cv" name="cv" required>
        </div>
        <button type="submit">Envoyer la candidature</button>
      </form>
    </div>
  </div>

  <script src="/projetWEB/MODEL-MVC/Public/js/stage.js"></script>
</body>
</html>