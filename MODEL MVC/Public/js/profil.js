// Récupérer les informations de l'utilisateur connecté
const userType = sessionStorage.getItem('user_type'); // Type d'utilisateur (candidat, pilote, entreprise)
const userId = sessionStorage.getItem('user_id'); // ID de l'utilisateur

if (!userType || !userId) {
    alert('Vous devez être connecté pour accéder à cette page.');
    window.location.href = '../connexion/connexion.html'; // Rediriger vers la page de connexion
}


// Charger les données dynamiques en fonction du type d'utilisateur
function chargerDonnees(type, id) {
    fetch(`../../back/auth/get_data.php?type=${type}&user_id=${id}`)
        .then(response => response.json())
        .then(data => {
            const content = document.getElementById('dynamic-content');
            if (type === 'candidat') {
                if (data.length > 0) {
                    content.innerHTML = `
                        <h3>Mes Candidatures</h3>
                        ${data.map(candidature => `
                            <p><strong>${candidature.titre}</strong> - Statut : ${candidature.statut}</p>
                        `).join('')}
                    `;
                } else {
                    content.innerHTML = '<p>Aucune candidature trouvée.</p>';
                }
            } else if (type === 'pilote') {
                if (data.length > 0) {
                    content.innerHTML = `
                        <h3>Activité des Élèves</h3>
                        ${data.map(eleves => `
                            <p><strong>${eleves.nom} ${eleves.prenom}</strong> - Candidatures : ${eleves.nb_candidatures}</p>
                        `).join('')}
                    `;
                } else {
                    content.innerHTML = '<p>Aucune activité trouvée pour vos élèves.</p>';
                }
            } else if (type === 'entreprise') {
                if (data.length > 0) {
                    content.innerHTML = `
                        <h3>Mes Offres Publiées</h3>
                        ${data.map(offre => `
                            <p><strong>${offre.titre}</strong> - Statut : ${offre.statut}</p>
                        `).join('')}
                    `;
                } else {
                    content.innerHTML = '<p>Aucune offre publiée.</p>';
                }
            }
        })
        .catch(error => console.error('Erreur lors de la récupération des données :', error));
}

// Charger le contenu en fonction du type d'utilisateur
const profileTitle = document.getElementById('profile-title');
if (userType === 'candidat') {
    profileTitle.textContent = 'Espace Candidat';
    chargerDonnees('candidat', userId);
} else if (userType === 'pilote') {
    profileTitle.textContent = 'Espace Pilote';
    chargerDonnees('pilote', userId);
} else if (userType === 'entreprise') {
    profileTitle.textContent = 'Espace Recruteur';
    chargerDonnees('entreprise', userId);
} else {
    profileTitle.textContent = 'Type d\'utilisateur inconnu';
    document.getElementById('dynamic-content').innerHTML = '<p>Type d\'utilisateur inconnu ou non connecté.</p>';
}