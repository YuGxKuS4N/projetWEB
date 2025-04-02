<?php
session_start();
require_once '../Config/config.php'; // Inclusion de la configuration

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'stagiaire') {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
    exit();
}

// Récupérer les données de la requête POST
$data = json_decode(file_get_contents('php://input'), true);
$stageId = $data['stageId'] ?? null;

if (!$stageId) {
    echo json_encode(['success' => false, 'message' => 'ID du stage manquant.']);
    exit();
}

$idStagiaire = $_SESSION['user_id']; // ID du stagiaire connecté

// Connexion à la base de données
$db = new Database();
$conn = $db->connect();

// Vérifier si le stage est déjà dans la wishlist
$stmt = $conn->prepare("SELECT * FROM wishlist WHERE id_stagiaire = ? AND id_stage = ?");
$stmt->bind_param("ii", $idStagiaire, $stageId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Ce stage est déjà dans votre wishlist.']);
    exit();
}

// Ajouter le stage à la wishlist
$stmt = $conn->prepare("INSERT INTO wishlist (id_stagiaire, id_stage) VALUES (?, ?)");
$stmt->bind_param("ii", $idStagiaire, $stageId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Stage ajouté à la wishlist.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout à la wishlist.']);
}

$stmt->close();
$conn->close();
?>