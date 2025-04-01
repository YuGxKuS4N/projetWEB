<?php
require_once '../../Controllers/c_get_data.php'; // Inclusion du contrôleur pour récupérer les données des élèves
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mes Élèves - WEB4ALL</title>
  <link rel="stylesheet" href="../../../Public/css/eleves.css">
</head>
<body>
  <header>
    <div class="logo">WEB4ALL</div>
    <nav>
      <a href="../acceuil/acceuil.php">Accueil</a>
      <a href="../creation_compte/connexion.php">Déconnexion</a>
    </nav>
  </header>

  <main>
    <section class="students">
      <h2>Mes Élèves</h2>
      <div id="students-container">
        <!-- Les élèves seront chargés dynamiquement ici -->
      </div>
    </section>
  </main>

  <script>
    // Charger les élèves dynamiquement
    document.addEventListener('DOMContentLoaded', () => {
      const userId = <?php echo $_SESSION['user_id']; ?>; // ID du pilote connecté
      const type = 'pilote';

      fetch(`../../Controllers/c_get_data.php?type=${type}&user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
          const container = document.getElementById('students-container');
          if (data.error) {
            container.innerHTML = `<p>${data.error}</p>`;
          } else {
            data.forEach(student => {
              const studentCard = `
                <div class="student-card">
                  <h3>${student.prenom} ${student.nom}</h3>
                  <p>Candidatures : ${student.nb_candidatures}</p>
                  <button onclick="viewStudentDetails(${student.id_etudiant})">Voir plus</button>
                </div>
              `;
              container.innerHTML += studentCard;
            });
          }
        })
        .catch(error => {
          console.error('Erreur lors du chargement des élèves :', error);
        });
    });

    // Fonction pour afficher les détails d'un élève
    function viewStudentDetails(studentId) {
      window.location.href = `details_eleve.php?id=${studentId}`;
    }
  </script>
</body>
</html>