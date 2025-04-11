<?php
session_start();
require __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Config/Database.php';

class CandidatureController {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->connect();
    }

    public function submitCandidature($etudiantId, $stageId, $cvFile, $motivationFile) {
        $errors = [];
        $dateCandidature = date('Y-m-d');

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
        if (!$stmt) {
            error_log("Erreur de préparation de la requête SQL : " . $this->conn->error);
            return ["errors" => ["Erreur interne du serveur."]];
        }
        $stmt->bind_param("iissss", $etudiantId, $stageId, $dateCandidature, $stageId, $cvPath, $motivationPath);

        if ($stmt->execute()) {
            return ["success" => "Votre candidature a été envoyée avec succès."];
        } else {
            return ["errors" => ["Erreur lors de l'enregistrement de la candidature."]];
        }
    }

    private function uploadFile($file, $uploadDirectory, &$errors) {
        $uploadDirectory = __DIR__ . '/../../Public/uploads/' . basename($uploadDirectory);
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

        if (!file_exists($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
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
    header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php");
    exit();
}

// Vérifier si la requête est une requête POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etudiantId = $_SESSION['user_id'];
    $stageId = intval($_POST['stage_id']);

    $database = new Database();
    $candidatureController = new CandidatureController($database);

    $response = $candidatureController->submitCandidature(
        $etudiantId,
        $stageId,
        $_FILES['cv'],
        $_FILES['motivation'] ?? null
    );

    // Rediriger vers postuler.php avec les messages de succès ou d'erreur
    if (isset($response['success'])) {
        header("Location: /projetWEB/MODEL-MVC/Views/stage/postuler.php?id=$stageId&success=" . urlencode($response['success']));
    } else {
        $errorMessage = implode(", ", $response['errors']);
        header("Location: /projetWEB/MODEL-MVC/Views/stage/postuler.php?id=$stageId&error=" . urlencode($errorMessage));
    }
    exit();
}
?>