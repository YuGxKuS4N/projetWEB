// Charger les stages depuis la base de données
document.addEventListener("DOMContentLoaded", () => {
  const offersContainer = document.getElementById("offers-container");

  fetch("../../Back/stage/get_stage.php")
    .then(response => response.json())
    .then(stages => {
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
          <button class="postuler-btn" onclick="redirectToPostuler(${stage.id})">Postuler</button>
        `;
        offersContainer.appendChild(offerCard);
      });
    })
    .catch(error => {
      console.error("Erreur lors du chargement des stages :", error);
      offersContainer.innerHTML = "<p>Erreur lors du chargement des stages.</p>";
    });
});

// Rediriger vers la page de candidature
function redirectToPostuler(stageId) {
  window.location.href = `postuler.html?stage_id=${stageId}`;
}