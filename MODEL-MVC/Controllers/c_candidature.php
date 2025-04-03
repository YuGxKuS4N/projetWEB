<?php
/**
 * Contrôleur pour gérer la soumission des candidatures.
 * 
 * - Vérifie si l'utilisateur est connecté.
 * - Téléverse les fichiers (CV et lettre de motivation).
 * - Enregistre la candidature dans la base de données.
 * - Utilise la classe `CandidatureController` pour encapsuler la logique.
 */


header('Content-Type: application/json');
require '../Config/config.php'; // Inclusion du fichier de configuration

class CandidatureController {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->connect();
    }

    public function submitCandidature($etudiantId, $stageId, $cvFile, $motivationFile) {
        $errors = [];
        $dateCandidature = date('Y-m-d'); // Date actuelle

        // Téléverser le CV
        $cvPath = $this->uploadFile($cvFile, '../../Public/uploads/cv/', $errors);

        // Téléverser la lettre de motivation
        $motivationPath = $this->uploadFile($motivationFile, '../../Public/uploads/motivation/', $errors);

        if (!empty($errors)) {
            return ["errors" => $errors];
        }

        // Enregistrer la candidature dans la base de données
        $sql = <<<SQL
            INSERT INTO Candidature 
                (id_etudiant_fk, id_offre_fk, date_candidature, statut_candidature, commentaire, id_entreprise_fk, cv_path, motivation_path)
            VALUES 
                (?, ?, ?, 'en attente', NULL, (SELECT id_entreprise_fk FROM Offre_Stage WHERE id_offre = ?), ?, ?)
SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iissss", $etudiantId, $stageId, $dateCandidature, $stageId, $cvPath, $motivationPath);

        if ($stmt->execute()) {
            return ["success" => "Votre candidature a été envoyée avec succès."];
        } else {
            return ["errors" => ["Erreur lors de l'enregistrement de la candidature."]];
        }
    }

    private function uploadFile($file, $uploadDirectory, &$errors) {
        $maxSize = 2 * 1024 * 1024; // 2 Mo
        $allowedExtensions = ['pdf'];

        if ($file['size'] > $maxSize) {
            $errors[] = "Le fichier " . $file['name'] . " est trop volumineux. La taille maximale est de 2 Mo.";
            return null;
        }

        $fileInfo = pathinfo($file['name']);
        $fileExtension = strtolower($fileInfo['extension']);
        if (!in_array($fileExtension, $allowedExtensions)) {
            $errors[] = "Le fichier " . $file['name'] . " doit être au format PDF.";
            return null;
        }

        $uploadPath = $uploadDirectory . uniqid() . '_' . basename($file['name']);
        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $errors[] = "Erreur lors du téléversement du fichier " . $file['name'] . ".";
            return null;
        }

        return $uploadPath;
    }
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Vous devez être connecté pour postuler."]);
    exit();
}

// Vérifier si la requête est une requête POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etudiantId = $_SESSION['user_id']; // Récupérer l'ID du stagiaire connecté
    $stageId = intval($_POST['stage_id']);

    $database = new Database();
    $candidatureController = new CandidatureController($database);

    $response = $candidatureController->submitCandidature(
        $etudiantId,
        $stageId,
        $_FILES['cv'],
        $_FILES['motivation']
    );

    echo json_encode($response);
}
?>