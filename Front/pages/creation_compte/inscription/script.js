

document.addEventListener("DOMContentLoaded", function () {
    const selectLocalite = document.getElementById("localite");

    fetch("https://geo.api.gouv.fr/communes?fields=nom&format=json&geometry=centre")
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur HTTP, statut : " + response.status);
            }
            return response.json();
        })
        .then(data => {
            selectLocalite.innerHTML = '<option selected disabled>Localité(s) recherchée(s)</option>';
            
            if (data.length === 0) {
                selectLocalite.innerHTML = '<option selected disabled>Aucune ville trouvée</option>';
                return;
            }

            data.forEach(ville => {
                let option = document.createElement("option");
                option.value = ville.nom;
                option.textContent = ville.nom;
                selectLocalite.appendChild(option);
            });
        })
        .catch(error => {
            console.error("❌ Erreur lors du chargement des villes :", error);
            selectLocalite.innerHTML = '<option selected disabled>Impossible de charger les villes</option>';
        });
});
