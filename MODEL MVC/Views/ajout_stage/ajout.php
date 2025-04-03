<?php

require_once '../../Config/config.php'; // Inclusion de la configuration

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

// Vérifier si un message de confirmation ou d'erreur est passé via la session
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']); // Supprimer le message après l'avoir affiché
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Créer une Offre de Stage</title>
  <link rel="stylesheet" href="../../../Public/css/ajout.css">
</head>
<body>
<div class="container">
  <h2>Créer une Offre de Stage</h2>

  <!-- Affichage du message de confirmation ou d'erreur -->
  <?php if (!empty($message)): ?>
    <div class="message">
      <p><?php echo $message; ?></p>
    </div>
  <?php endif; ?>

  <form action="../../Controllers/c_ajout_stage.php" method="POST">
    <label for="titre">Titre de l’offre</label>
    <input type="text" id="titre" name="titre" placeholder="Titre de l'offre" required>

    <label for="description">Description de l’offre</label>
    <textarea id="description" name="description" placeholder="Description ..." rows="5" required></textarea>

    <label for="secteur">Secteur d'activité</label>
    <select id="secteur" name="secteur" required>
      <option value="">Sélectionnez un secteur</option>
      <?php foreach ($secteurs as $secteur): ?>
        <option value="<?php echo $secteur; ?>"><?php echo $secteur; ?></option>
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