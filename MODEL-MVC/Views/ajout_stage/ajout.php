<?php
require_once dirname(__DIR__, 2) . '/Config/config.php'; // Correction du chemin

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

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenir la connexion à la base de données
    $conn = getDatabaseConnection();

    // Vérifier que la connexion est active
    if (!$conn || $conn->connect_error) {
        $_SESSION['message'] = "Erreur de connexion à la base de données.";
        header("Location: /projetWEB/MODEL-MVC/Views/ajout_stage/ajout.php");
        exit();
    }

    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $secteur_activite = htmlspecialchars($_POST['secteur']);
    $date_debut = htmlspecialchars($_POST['date_debut']);
    $duree = intval($_POST['duree']);
    $lieu = htmlspecialchars($_POST['lieu_stage']);

    // Insérer les données dans la table Offre_Stage
    $sql = <<<SQL
    INSERT INTO Offre_Stage (titre, description, secteur_activite, date_debut, duree, lieu)
    VALUES (?, ?, ?, ?, ?, ?)
SQL;

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssssis", $titre, $description, $secteur_activite, $date_debut, $duree, $lieu);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Données insérées avec succès dans la table Offre_Stage.";
        } else {
            $_SESSION['message'] = "Erreur lors de l'insertion des données : " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['message'] = "Erreur lors de la préparation de la requête : " . $conn->error;
    }

    // Redirection après traitement
    header("Location: /projetWEB/MODEL-MVC/Views/ajout_stage/ajout.php");
    exit();
}
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
<div class="container">
  <h2>Créer une Offre de Stage</h2>

  <!-- Affichage du message de confirmation ou d'erreur -->
  <?php if (!empty($message)): ?>
    <div class="message">
      <p><?php echo htmlspecialchars($message); ?></p>
    </div>
  <?php endif; ?>

  <form action="/projetWEB/MODEL-MVC/Views/ajout_stage/ajout.php" method="POST">
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
