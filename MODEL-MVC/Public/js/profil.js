document.addEventListener('DOMContentLoaded', () => {
  const userId = sessionStorage.getItem('user_id');
  const userType = sessionStorage.getItem('role');

  if (!userId || !userType) {
    alert('Vous devez être connecté pour accéder à cette page.');
    window.location.href = '/projetWEB/MODEL-MVC/Views/creation_compte/c_connexion.php';
    return;
  }

  // Charger les informations utilisateur (contexte : profil)
  fetch(`/projetWEB/MODEL-MVC/Controllers/c_get_data.php?type=${userType}&user_id=${userId}&context=profile`)
    .then(response => response.json())
    .then(data => {
      const container = document.getElementById('dynamic-content');
      if (data.error) {
        container.innerHTML = `<p>${data.error}</p>`;
      } else {
        // Afficher les informations utilisateur
        let content = '';
        for (const [key, value] of Object.entries(data[0])) {
          content += `
            <div class="profile-field">
              <label for="${key}">${key}</label>
              <input type="text" id="${key}" value="${value}" />
            </div>
          `;
        }
        content += `<button id="save-profile">Enregistrer</button>`;
        container.innerHTML = content;

        // Ajouter un événement pour enregistrer les modifications
        document.getElementById('save-profile').addEventListener('click', () => {
          const updatedData = {};
          document.querySelectorAll('.profile-field input').forEach(input => {
            updatedData[input.id] = input.value;
          });

          fetch(`/projetWEB/MODEL-MVC/Controllers/update_profile.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ userId, userType, ...updatedData })
          })
            .then(response => response.json())
            .then(result => {
              if (result.success) {
                alert('Profil mis à jour avec succès.');
              } else {
                alert('Erreur lors de la mise à jour du profil.');
              }
            })
            .catch(error => console.error('Erreur :', error));
        });
      }
    })
    .catch(error => console.error('Erreur lors du chargement des données utilisateur :', error));
});
