<?php
require '../Config/config.php'; // Inclure la connexion à la base de données

// Vérifier si le paramètre 'type' est défini
if (!isset($_GET['type']) || !isset($_GET['user_id'])) {
    echo json_encode(["error" => "Paramètres manquants."]);
    exit;
}

$type = $_GET['type']; // Type d'utilisateur (candidat, pilote, entreprise)
$userId = intval($_GET['user_id']); // ID de l'utilisateur
$context = $_GET['context'] ?? 'profile'; // Contexte : 'profile' ou 'students'

$response = [];

if ($type === 'candidat' && $context === 'profile') {
    // Récupérer les informations du profil du candidat
    $stmt = $conn->prepare("SELECT * FROM Etudiant WHERE id_etudiant = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
} elseif ($type === 'pilote' && $context === 'profile') {
    // Récupérer les informations du profil du pilote
    $stmt = $conn->prepare("SELECT * FROM Pilote WHERE id_pilote = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
} elseif ($type === 'pilote' && $context === 'students') {
    // Récupérer les élèves du pilote
    $stmt = $conn->prepare("
        SELECT e.nom, e.prenom, COUNT(c.id_candidature) AS nb_candidatures
        FROM Etudiant e
        LEFT JOIN Candidature c ON e.id_etudiant = c.id_etudiant_fk
        WHERE e.id_pilote_fk = ?
        GROUP BY e.id_etudiant
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
} elseif ($type === 'entreprise' && $context === 'profile') {
    // Récupérer les informations du profil de l'entreprise
    $stmt = $conn->prepare("SELECT * FROM Entreprise WHERE id_entreprise = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
} else {
    echo json_encode(["error" => "Type d'utilisateur ou contexte invalide."]);
    exit;
}

// Retourner les données au format JSON
echo json_encode($response);
?>