<!-- filepath: c:\wamp64\www\projetWEB\MODEL-MVC\Views\stage\stage.php -->
<?php
// Inclure la configuration et démarrer la session
require_once dirname(__DIR__, 3) . '/MODEL-MVC/Config/Database.php';
require_once dirname(__DIR__, 3) . '/MODEL-MVC/Config/config.php';

session_start();

// Récupérer les données des stages via le contrôleur
$stages = [];
try {
    $database = new Database();
    $conn = $database->connect();

    $sql = "SELECT * FROM Offre_Stage";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $stages[] = $row;
        }
    } else {
        $stages = ["error" => "Aucune offre de stage disponible."];
    }
} catch (Exception $e) {
    error_log("Erreur lors de la récupération des stages : " . $e->getMessage());
    $stages = ["error" => "Erreur lors de la récupération des données."];
}
?>
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
    </nav>
  </header>

  <main>
    <section class="offers">
      <h2>Nos Offres de Stage</h2>
      <div id="offers-container" class="offers-list">
        <?php if (isset($stages['error'])): ?>
          <p class="error-message"><?php echo htmlspecialchars($stages['error']); ?></p>
        <?php else: ?>
          <?php foreach ($stages as $stage): ?>
            <div class="offer">
              <h3><?php echo htmlspecialchars($stage['titre']); ?></h3>
              <p><?php echo htmlspecialchars($stage['description']); ?></p>
              <p><strong>Secteur :</strong> <?php echo htmlspecialchars($stage['secteur_activite']); ?></p>
              <p><strong>Date de début :</strong> <?php echo htmlspecialchars($stage['date_debut']); ?></p>
              <p><strong>Durée :</strong> <?php echo htmlspecialchars($stage['duree']); ?> mois</p>
              <p><strong>Lieu :</strong> <?php echo htmlspecialchars($stage['lieu_stage']); ?></p>
              <p>
                <button class="postuler-btn" data-stage-id="<?php echo htmlspecialchars((int)$stage['id_offre']); ?>" data-stage-title="<?php echo htmlspecialchars($stage['titre']); ?>">
                  Postuler
                </button>
              </p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <script src="/projetWEB/MODEL-MVC/Public/js/stage.js"></script>
</body>
</html>