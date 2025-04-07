<?php
// filepath: c:\projetWEB\MODEL-MVC\Views\stage\stage.php
require_once dirname(__DIR__, 2) . '/Config/config.php'; // Correction du chemin

try {
    $conn = getDatabaseConnection(); // Utilisation de la fonction pour obtenir la connexion

    // Récupérer les offres de stage
    $query = $conn->query("SELECT * FROM Offre_Stage");
    $offres = $query->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
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
      <a href="/projetWEB/MODEL-MVC/Views/creation_compte/entreprise.php">Entreprises</a>
    </nav>
  </header>

  <main>
    <section class="search-filters">
      <input type="text" id="search-input" placeholder="Rechercher un stage...">
      <select id="filter-lieu">
        <option value="">Lieu</option>
      </select>
      <select id="filter-duree">
        <option value="">Durée</option>
      </select>
      <select id="filter-profil">
        <option value="">Profil demandé</option>
      </select>
      <button id="search-button">Rechercher</button>
    </section>

    <section class="offers">
  <h2>Nos Offres de Stage</h2>
  <div class="offers-list">
    <?php if (!empty($offres)): ?>
      <?php foreach ($offres as $offre): ?>
        <div class="offer">
          <h3><?php echo htmlspecialchars($offre['titre']); ?></h3>
          <p><?php echo htmlspecialchars($offre['description']); ?></p>
          <p><strong>Secteur :</strong> <?php echo htmlspecialchars($offre['secteur_activite']); ?></p>
          <p><strong>Date de début :</strong> <?php echo htmlspecialchars($offre['date_debut']); ?></p>
          <p><strong>Durée :</strong> <?php echo htmlspecialchars($offre['duree']); ?> mois</p>
          <p><strong>Lieu :</strong> <?php echo htmlspecialchars($offre['lieu']); ?></p>
            <p><button onclick="window.location.href='/projetWEB/MODEL-MVC/Views/postuler/postuler.php?id=<?php echo htmlspecialchars($offre['id']); ?>'">Postuler</button></p>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Aucune offre de stage disponible pour le moment.</p>
    <?php endif; ?>
  </div>
</section>



  </main>
  <script src="/projetWEB/MODEL-MVC/Public/js/stage.js"></script>
</body>
</html>
