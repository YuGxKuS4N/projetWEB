document.addEventListener("DOMContentLoaded", () => {
  const offersContainer = document.getElementById("offers-container");
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

        // Charger les options des lieux
        options.lieux.forEach(lieu => {
          const option = document.createElement("option");
          option.value = lieu;
          option.textContent = lieu;
          filterLieu.appendChild(option);
        });

        // Charger les options des durées
        options.durees.forEach(duree => {
          const option = document.createElement("option");
          option.value = duree;
          option.textContent = `${duree} mois`;
          filterDuree.appendChild(option);
        });

        // Charger les options des profils
        options.profils.forEach(profil => {
          const option = document.createElement("option");
          option.value = profil;
          option.textContent = profil;
          filterProfil.appendChild(option);
        });
      })
      .catch(error => console.error("Erreur lors du chargement des options de filtrage :", error));
  };

  const loadStages = (filters) => {
    const query = new URLSearchParams(filters).toString();
    fetch(`/projetWEB/MODEL-MVC/Controllers/c_get_stage.php?${query}`)
      .then(response => {
        if (!response.ok) {
          throw new Error(`Erreur HTTP : ${response.status}`);
        }
        return response.json();
      })
      .then(stages => {
        if (stages.error) {
          console.error("Erreur API :", stages.error);
          return;
        }

        offersContainer.innerHTML = ""; // Réinitialiser le conteneur
        stages.forEach(stage => {
          const stageElement = document.createElement("div");
          stageElement.className = "offer";
          stageElement.innerHTML = `
            <h3>${stage.titre}</h3>
            <p>${stage.description}</p>
            <p><strong>Secteur :</strong> ${stage.secteur_activite}</p>
            <p><strong>Date de début :</strong> ${stage.date_debut}</p>
            <p><strong>Durée :</strong> ${stage.duree} mois</p>
            <p><strong>Lieu :</strong> ${stage.lieu}</p>
            <button class="postuler-btn" data-stage-id="${stage.id_offre}" data-stage-title="${stage.titre}">Postuler</button>
          `;
          offersContainer.appendChild(stageElement);
        });
      })
      .catch(error => console.error("Erreur lors du chargement des stages :", error));
  };

  loadFilterOptions();

  searchButton.addEventListener("click", () => {
    const filters = {
      lieu: filterLieu.value,
      duree: filterDuree.value,
      profil: filterProfil.value
    };

    loadStages(filters);
  });
});