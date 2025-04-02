<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pilote') {
    header("Location: ../creation_compte/connexion.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mes Élèves</title>
  <link rel="stylesheet" href="../../../Public/css/eleves.css">
</head>
<body>
<div class="container">
  <h2>Mes Élèves</h2>
  <div id="students-container">
    <!-- Les élèves seront chargés dynamiquement ici -->
  </div>
</div>
<script>
  // Charger les élèves dynamiquement
  document.addEventListener('DOMContentLoaded', () => {
    const userId = <?php echo $_SESSION['user_id']; ?>; // ID du pilote connecté
    const type = 'pilote';

    fetch(`../../Controllers/c_get_data.php?type=${type}&user_id=${userId}&context=students`)
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
</script>
</body>
</html>