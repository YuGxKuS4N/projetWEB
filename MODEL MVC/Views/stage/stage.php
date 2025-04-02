<?php
require_once '../../Controllers/get_stage.php'; // Inclusion du contrôleur pour récupérer les stages
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stages - WEB4ALL</title>
  <link rel="stylesheet" href="../../../Public/css/stage.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
  <header>
    <div class="logo">WEB4ALL</div>
    <nav>
      <a href="../acceuil/acceuil.php">Accueil</a>
      <a href="../creation_compte/entreprise.php">Entreprises</a>
    </nav>
  </header>

  <main>
    <section class="offers">
      <h2>Nos Offres de Stage</h2>
      <div id="offers-container">
        <!-- Les offres seront chargées dynamiquement ici -->
      </div>
    </section>
  </main>
  <script src="../../../Public/js/stage.js"></script>
</body>
</html>