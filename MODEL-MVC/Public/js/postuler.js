// Récupérer les paramètres de l'URL
const params = new URLSearchParams(window.location.search);
const stageId = params.get("stage_id");

// Charger les détails du stage
document.addEventListener("DOMContentLoaded", () => {
  fetch(`/projetWEB/MODEL-MVC/Controllers/c_get_stage.php?stage_id=${stageId}`)
    .then(response => response.json())
    .then(stage => {
      if (stage.error) {
        console.error("Erreur :", stage.error);
        document.getElementById("stage-title").textContent = "Erreur lors du chargement du stage.";
      } else {
        document.getElementById("stage-title").textContent = `Postuler pour : ${stage.entreprise} (${stage.lieu})`;
        document.getElementById("stage-id").value = stage.id;
      }
    })
    .catch(error => console.error("Erreur lors du chargement des détails du stage :", error));
});

