document.addEventListener("DOMContentLoaded", () => {
  const offersContainer = document.getElementById("offers-container");
  const searchInput = document.getElementById("search-input");
  const filterLieu = document.getElementById("filter-lieu");
  const filterDuree = document.getElementById("filter-duree");
  const filterProfil = document.getElementById("filter-profil");
  const searchButton = document.getElementById("search-button");

  // Fonction pour charger les options de filtrage
  const loadFilterOptions = () => {
    fetch('/projetWEB/MODEL-MVC/Controllers/c_get_stage.php?action=getFilters')
      .then(response => response.json())
      .then(options => {
        // Charger les lieux
        options.lieux.forEach(lieu => {
          const option = document.createElement("option");
          option.value = lieu;
          option.textContent = lieu;
          filterLieu.appendChild(option);
        });

        // Charger les durées
        options.durees.forEach(duree => {
          const option = document.createElement("option");
          option.value = duree;
          option.textContent = `${duree} mois`;
          filterDuree.appendChild(option);
        });

        // Charger les profils demandés
        options.profils.forEach(profil => {
          const option = document.createElement("option");
          option.value = profil;
          option.textContent = profil;
          filterProfil.appendChild(option);
        });
      })
      .catch(error => console.error("Erreur lors du chargement des options de filtrage :", error));
  };

  // Fonction pour charger les stages
  const loadStages = (search = '', filters = {}) => {
    const params = new URLSearchParams({
      search: search,
      lieu: filters.lieu || '',
      duree: filters.duree || '',
      profil: filters.profil || ''
    });

    fetch(`/projetWEB/MODEL-MVC/Controllers/c_get_stage.php?${params.toString()}`)
      .then(response => response.json())
      .then(stages => {
        offersContainer.innerHTML = ''; // Réinitialiser le conteneur

        if (stages.error) {
          console.error(stages.error);
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

  // Charger les options de filtrage au démarrage
  loadFilterOptions();

  // Charger les stages au démarrage
  loadStages();

  // Ajouter un événement pour la recherche
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

// Rediriger vers la page de candidature
function redirectToPostuler(stageId) {
  window.location.href = `/projetWEB/MODEL-MVC/Views/stage/postuler.html?stage_id=${stageId}`;
}
