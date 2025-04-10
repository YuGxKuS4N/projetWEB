<?php
// filepath: c:\projetWEB\MODEL-MVC\Views\stage\stage.php
require_once dirname(__DIR__, 2) . '/Config/config.php'; // Correction du chemin

try {
    $conn = getDatabaseConnection(); // Utilisation de la fonction pour obtenir la connexion

    // Récupérer les offres de stage
    $query = $conn->query("SELECT * FROM Offre_Stage");
    $offres = $query->fetch_all(MYSQLI_ASSOC);

    // Journaliser les données pour le débogage
    error_log("Offres récupérées : " . json_encode($offres));
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
                <button onclick="window.location.href='/projetWEB/MODEL-MVC/Views/stage/postuler.php?id=<?php echo htmlspecialchars((int)$offre['id_offre']); ?>'">
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

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const offersContainer = document.getElementById("offers-container");
      const searchInput = document.getElementById("search-input");
      const filterLieu = document.getElementById("filter-lieu");
      const filterDuree = document.getElementById("filter-duree");
      const filterProfil = document.getElementById("filter-profil");
      const searchButton = document.getElementById("search-button");

      const loadFilterOptions = () => {
        fetch('/projetWEB/MODEL-MVC/Controllers/c_get_stage.php?action=getFilters')
          .then(response => {
            if (!response.ok) {
              throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return response.json();
          })
          .then(options => {
            if (options.error) {
              console.error("Erreur API :", options.error);
              return;
            }

            options.lieux.forEach(lieu => {
              const option = document.createElement("option");
              option.value = lieu;
              option.textContent = lieu;
              filterLieu.appendChild(option);
            });

            options.durees.forEach(duree => {
              const option = document.createElement("option");
              option.value = duree;
              option.textContent = `${duree} mois`;
              filterDuree.appendChild(option);
            });

            options.profils.forEach(profil => {
              const option = document.createElement("option");
              option.value = profil;
              option.textContent = profil;
              filterProfil.appendChild(option);
            });
          })
          .catch(error => console.error("Erreur lors du chargement des options de filtrage :", error));
      };

      const loadStages = (search = '', filters = {}) => {
        const params = new URLSearchParams({
          search: search,
          lieu: filters.lieu || '',
          duree: filters.duree || '',
          profil: filters.profil || ''
        });

        fetch(`/projetWEB/MODEL-MVC/Controllers/c_get_stage.php?${params.toString()}`)
          .then(response => {
            if (!response.ok) {
              throw new Error(`Erreur HTTP : ${response.status}`);
            }
            return response.json();
          })
          .then(stages => {
            offersContainer.innerHTML = '';

            if (stages.error) {
              console.error("Erreur API :", stages.error);
              offersContainer.innerHTML = "<p>Erreur lors du chargement des stages.</p>";
              return;
            }

            stages.forEach(stage => {
              const offerCard = document.createElement("div");
              offerCard.classList.add("offer-card");
              offerCard.innerHTML = `
                <h3>${stage.titre}</h3>
                <p><strong>Entreprise :</strong> ${stage.entreprise}</p>
                <p><strong>Lieu :</strong> ${stage.lieu}</p>
                <p><strong>Durée :</strong> ${stage.duree} mois</p>
                <p><strong>Profil demandé :</strong> ${stage.profil}</p>
                <button class="postuler-btn" onclick="redirectToPostuler(${stage.id})">Postuler</button>
              `;
              offersContainer.appendChild(offerCard);
            });
          })
          .catch(error => {
            console.error("Erreur lors du chargement des stages :", error);
            offersContainer.innerHTML = "<p>Erreur lors du chargement des stages.</p>";
          });
      };

      loadFilterOptions();
      loadStages();

      searchButton.addEventListener("click", () => {
        const search = searchInput.value;
        const filters = {
          lieu: filterLieu.value,
          duree: filterDuree.value,
          profil: filterProfil.value
        };
        loadStages(search, filters);
      });
    });

    function redirectToPostuler(stageId) {
      window.location.href = `/projetWEB/MODEL-MVC/Views/stage/postuler.php?stage_id=${stageId}`;
    }
  </script>
</body>
</html>