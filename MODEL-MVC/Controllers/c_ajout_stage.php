<?php
session_start();

// Vérifier si l'utilisateur est connecté et est une entreprise
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entreprise') {
    // Rediriger vers la page de connexion si non connecté ou si le rôle est incorrect
    header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php");
    exit();
}

// Vérifier si un message de confirmation ou d'erreur est passé via la session
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']); // Supprimer le message après l'avoir affiché

// Options dynamiques pour les secteurs d'activité
$secteurs = [
    "Développement Web",
    "Développement Mobile",
    "Intelligence Artificielle",
    "Cybersécurité",
    "Big Data",
    "Réseaux et Télécommunications",
    "Cloud Computing",
    "Internet des Objets (IoT)",
    "Réalité Virtuelle et Augmentée",
    "Gestion de Projets Informatiques"
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Créer une Offre de Stage</title>
  <link rel="stylesheet" href="/projetWEB/MODEL-MVC/Public/css/ajout.css">
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="nav-logo">
        <a href="/projetWEB/MODEL-MVC/Views/acceuil/acceuil.php">Accueil</a>
      </div>
    </nav>
  </header>

  <div class="container">
    <h2>Créer une Offre de Stage</h2>

    <!-- Affichage du message de confirmation ou d'erreur -->
    <?php if (!empty($message)): ?>
      <div class="message">
        <p><?php echo htmlspecialchars($message); ?></p>
      </div>
    <?php endif; ?>

    <!-- Formulaire pour créer une offre de stage -->
    <form action="/projetWEB/MODEL-MVC/Controllers/c_ajout_stage.php" method="POST">
      <input type="hidden" name="form_submitted" value="1">
      <label for="titre">Titre de l’offre</label>
      <input type="text" id="titre" name="titre" placeholder="Titre de l'offre" required>

      <label for="description">Description de l’offre</label>
      <textarea id="description" name="description" placeholder="Description ..." rows="5" required></textarea>

      <label for="secteur">Secteur d'activité</label>
      <select id="secteur" name="secteur" required>
        <option value="">Sélectionnez un secteur</option>
        <?php foreach ($secteurs as $secteur): ?>
          <option value="<?php echo htmlspecialchars($secteur); ?>"><?php echo htmlspecialchars($secteur); ?></option>
        <?php endforeach; ?>
      </select>

      <label for="date_debut">Date de début</label>
      <input type="date" id="date_debut" name="date_debut" required>

      <label for="duree">Durée (en mois)</label>
      <input type="number" id="duree" name="duree" placeholder="Durée en mois" required>

      <label for="lieu_stage">Lieu du stage</label>
      <input type="text" id="lieu_stage" name="lieu_stage" placeholder="Lieu du stage" required>

      <button type="submit">Publier l’offre</button>
    </form>
  </div>
</body>
</html>