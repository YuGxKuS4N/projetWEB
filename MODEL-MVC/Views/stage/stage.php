<?php


session_start();

// Récupérer les données des stages via le contrôleur
$offres = [];
try {
    // Utilisez l'URL complète pour accéder au contrôleur
    $url = "http://localhost/projetWEB/MODEL-MVC/Controllers/c_get_stage.php";
    $response = file_get_contents($url);

    if ($response === false) {
        throw new Exception("Erreur lors de la récupération des données.");
    }

    $offres = json_decode($response, true);

    if (isset($offres['error'])) {
        error_log("Erreur API : " . $offres['error']);
        echo "<p>Erreur API : " . htmlspecialchars($offres['error']) . "</p>"; // Affiche l'erreur API
        $offres = [];
    }
} catch (Exception $e) {
    error_log("Erreur lors de la récupération des stages : " . $e->getMessage());
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage()) . "</p>"; // Affiche l'erreur PHP
    $offres = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stages - WEB4ALL</title>
  <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/stage.css">
</head>
<body>
  <header>
    <div class="logo">WEB4ALL</div>
    <nav>
      <a href="/projetWEB/MODEL-MVC/Views/acceuil/acceuil.php">Accueil</a>
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
                <button class="postuler-btn" data-stage-id="<?php echo htmlspecialchars((int)$offre['id']); ?>" data-stage-title="<?php echo htmlspecialchars($offre['titre']); ?>">
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
</body>
</html>