<?php
session_start();
require_once '../Config/config.php'; // Inclusion de la configuration

// Vérifier si l'utilisateur est connecté et est une entreprise
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entreprise') {
    $_SESSION['message'] = "Accès non autorisé. Vous devez être connecté en tant qu'entreprise.";
    header("Location: ../Views/ajout_stage/ajout.php");
    exit();
}

// Créer une instance de la classe Database
$db = new Database();
$conn = $db->connect(); // Établir la connexion

// Récupérer les données du formulaire
$titre = htmlspecialchars(trim($_POST['titre']));
$description = htmlspecialchars(trim($_POST['description']));
$secteur = htmlspecialchars(trim($_POST['secteur']));
$date_debut = htmlspecialchars(trim($_POST['date_debut']));
$duree = (int) htmlspecialchars(trim($_POST['duree']));
$lieu_stage = htmlspecialchars(trim($_POST['lieu_stage']));
$id_entreprise = $_SESSION['user_id']; // ID de l'entreprise connectée

// Insérer l'offre de stage dans la base de données
$stmt = $conn->prepare("
    INSERT INTO Offre_Stage (titre, description, secteur, date_publi, date_debut, duree, lieu_stage, id_entreprise_fk)
    VALUES (?, ?, ?, NOW(), ?, ?, ?, ?)
");
$stmt->bind_param("ssssisi", $titre, $description, $secteur, $date_debut, $duree, $lieu_stage, $id_entreprise);

if ($stmt->execute()) {
    $_SESSION['message'] = "Offre de stage publiée avec succès.";
} else {
    $_SESSION['message'] = "Erreur lors de la publication de l'offre : " . $stmt->error;
}

$stmt->close();
$conn->close();

// Rediriger vers la page d'ajout avec un message
header("Location: ../Views/ajout_stage/ajout.php");
exit();
?>