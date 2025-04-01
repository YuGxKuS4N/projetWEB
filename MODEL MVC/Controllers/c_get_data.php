<?php
require '../Config/config.php'; // Inclure la connexion à la base de données

// Vérifier si le paramètre 'type' est défini
if (!isset($_GET['type']) || !isset($_GET['user_id'])) {
    echo json_encode(["error" => "Paramètres manquants."]);
    exit;
}

$type = $_GET['type']; // Type d'utilisateur (candidat, pilote, entreprise)
$userId = intval($_GET['user_id']); // ID de l'utilisateur

$response = [];

if ($type === 'candidat') {
    // Récupérer les candidatures du stagiaire
    $stmt = $conn->prepare("SELECT titre, statut FROM Candidature WHERE id_etudiant_fk = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
} elseif ($type === 'pilote') {
    // Récupérer l'activité des élèves pour le pilote
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
} elseif ($type === 'entreprise') {
    // Récupérer les offres publiées par l'entreprise
    $stmt = $conn->prepare("SELECT titre, statut FROM Offre_Stage WHERE id_entreprise_fk = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
} else {
    echo json_encode(["error" => "Type d'utilisateur invalide."]);
    exit;
}

// Retourner les données au format JSON
echo json_encode($response);
?>