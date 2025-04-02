// Charger les stages depuis la base de données
document.addEventListener("DOMContentLoaded", () => {
  const offersContainer = document.getElementById("offers-container");

  fetch("../../Back/stage/c_get_stage.php")
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
          <div class="wishlist-icon" onclick="addToWishlist(${stage.id})">
            <i class="fa fa-star"></i>
          </div>
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

// Ajouter un stage à la wishlist
function addToWishlist(stageId) {
  fetch("../../Controllers/wishlist.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ stageId })
  })
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        alert("Stage ajouté à la wishlist !");
      } else {
        alert("Erreur : " + result.message);
      }
    })
    .catch(error => {
      console.error("Erreur lors de l'ajout à la wishlist :", error);
    });
}