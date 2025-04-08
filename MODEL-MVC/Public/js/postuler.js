document.addEventListener("DOMContentLoaded", () => {
  console.log("Script postuler.js chargé avec succès."); // Vérification du chargement

  const stageId = new URLSearchParams(window.location.search).get("id");
  if (!stageId) {
    alert("ID du stage manquant dans l'URL.");
    return;
  }

  document.getElementById("stage-id").value = stageId;

  fetch(`/projetWEB/MODEL-MVC/Controllers/c_get_stage.php?stage_id=${stageId}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`Erreur HTTP : ${response.status}`);
      }
      return response.json();
    })
    .then(stage => {
      if (stage.error) {
        console.error("Erreur :", stage.error);
        document.getElementById("stage-title").textContent = "Erreur lors du chargement du stage.";
      } else {
        document.getElementById("stage-title").textContent = `Postuler pour : ${stage.titre}`;
      }
    })
    .catch(error => console.error("Erreur lors du chargement des détails du stage :", error));
});

