// Gérer les comptes
function manageAccounts() {
    fetch('/path/to/your/project/Controllers/c_admin.php?action=getAccounts')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                console.log("Comptes :", data);
                alert("Les comptes ont été chargés dans la console.");
            }
        })
        .catch(error => console.error("Erreur lors du chargement des comptes :", error));
}

// Gérer les stages
function manageStages() {
    fetch('/path/to/your/project/Controllers/c_admin.php?action=getStages')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                console.log("Stages :", data);
                alert("Les stages ont été chargés dans la console.");
            }
        })
        .catch(error => console.error("Erreur lors du chargement des stages :", error));
}
