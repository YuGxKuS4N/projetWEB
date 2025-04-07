document.addEventListener('DOMContentLoaded', () => {
  const userId = sessionStorage.getItem('user_id') || null;
  const userType = sessionStorage.getItem('role') || null;

  if (!userId || !userType) {
    alert('Vous devez être connecté pour accéder à cette page.');
    window.location.href = '/projetWEB/MODEL-MVC/Views/creation_compte/connexion.php';
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
        for (const [key, value] of Object.entries(data)) {
          content += `
            <div class="profile-field">
              <label for="${key}">${key}</label>
              <input type="text" id="${key}" value="${value}" readonly />
            </div>
          `;
        }
        container.innerHTML = content;
      }
    })
    .catch(error => console.error('Erreur lors du chargement des données utilisateur :', error));
});
