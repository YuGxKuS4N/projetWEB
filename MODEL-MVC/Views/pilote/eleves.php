<?php
session_start(); // Assure que la session est démarrée

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pilote') {
    header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mes Élèves</title>
  <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/eleves.css">
</head>
<body>
<div class="container">
  <h2>Mes Élèves</h2>
  <div id="students-container">
    <!-- Les élèves seront chargés dynamiquement ici -->
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const userId = <?php echo json_encode($_SESSION['user_id']); ?>;
    const promoYear = <?php echo json_encode($_SESSION['annee_promo']); ?>;

    fetch(`/projetWEB/MODEL-MVC/Controllers/c_get_data.php?context=students&promo_year=${promoYear}`)
      .then(response => {
        if (!response.ok) {
          throw new Error(`Erreur HTTP : ${response.status}`);
        }
        return response.json().catch(() => {
          throw new Error("La réponse n'est pas un JSON valide.");
        });
      })
      .then(data => {
        const container = document.getElementById('students-container');
        if (data.error) {
          container.innerHTML = `<p>${data.error}</p>`;
        } else if (Array.isArray(data) && data.length > 0) {
          data.forEach(student => {
            const studentCard = `
              <div class="student-card">
                <h3>${student.prenom} ${student.nom}</h3>
                <p>Candidatures : ${student.nb_candidatures}</p>
              </div>
            `;
            container.innerHTML += studentCard;
          });
        } else {
          container.innerHTML = `<p>Aucun élève trouvé pour cette année de promotion.</p>`;
        }
      })
      .catch(error => {
        console.error('Erreur lors du chargement des élèves :', error);
        document.getElementById('students-container').innerHTML = `<p>Erreur lors du chargement des élèves. Veuillez réessayer plus tard.</p>`;
      });
  });
</script>
</body>
</html>
