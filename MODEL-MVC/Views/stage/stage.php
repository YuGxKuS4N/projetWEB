<?php
// filepath: c:\projetWEB\MODEL-MVC\Views\stage\stage.php
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
      <div id="offers-container">
        <!-- Les offres seront chargées dynamiquement ici -->
      </div>
    </section>
  </main>
  <script src="/projetWEB/MODEL-MVCw/Public/js/stage.js"></script>
</body>
</html>
