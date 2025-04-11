<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Démarre la session uniquement si elle n'est pas déjà active
}
/**
 * Contrôleur pour gérer la soumission des candidatures.
 * 
 * - Vérifie si l'utilisateur est connecté.
 * - Téléverse les fichiers (CV et lettre de motivation).
 * - Enregistre la candidature dans la base de données.
 * - Utilise la classe `CandidatureController` pour encapsuler la logique.
 */

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../Config/config.php'; // Inclusion du fichier de configuration
require_once __DIR__ . '/../Config/Database.php'; // Inclusion correcte de la classe Database

class CandidatureController {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->connect();
    }

    private function saveFileToServer($file, $uploadDirectory, &$errors) {
        $maxSize = 2 * 1024 * 1024; // 2 Mo
        $allowedExtensions = ['pdf'];

        // Vérifier les erreurs de téléversement
        if ($file['error'] !== UPLOAD_ERR_OK) {
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $errors[] = "Le fichier " . $file['name'] . " dépasse la taille maximale autorisée.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $errors[] = "Le fichier " . $file['name'] . " n'a été que partiellement téléchargé.";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $errors[] = "Aucun fichier n'a été téléchargé.";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $errors[] = "Le dossier temporaire est manquant.";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $errors[] = "Échec de l'écriture du fichier " . $file['name'] . " sur le disque.";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $errors[] = "Une extension PHP a arrêté le téléversement du fichier " . $file['name'] . ".";
                    break;
                default:
                    $errors[] = "Erreur inconnue lors du téléversement du fichier " . $file['name'] . ".";
            }
            return null;
        }

        // Vérifier si le fichier est valide
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            $errors[] = "Aucun fichier n'a été téléchargé.";
            return null;
        }

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

        // Créer le répertoire si nécessaire
        if (!file_exists($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        // Générer un nom de fichier unique
        $fileName = uniqid() . '_' . basename($file['name']);
        $filePath = $uploadDirectory . $fileName;

        // Déplacer le fichier téléchargé
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            $errors[] = "Erreur lors de l'enregistrement du fichier " . $file['name'] . ".";
            return null;
        }

        return $filePath;
    }

    public function submitCandidature($etudiantId, $stageId, $cvFile, $motivationFile) {
        $errors = [];
        $dateCandidature = date('Y-m-d'); // Date actuelle

        // Récupérer l'ID de l'entreprise associée à l'offre de stage
        $sqlEntreprise = "SELECT id_entreprise_fk FROM Offre_Stage WHERE `stage-id` = ?";
        $stmtEntreprise = $this->conn->prepare($sqlEntreprise);
        $stmtEntreprise->bind_param("i", $stageId);
        $stmtEntreprise->execute();
        $resultEntreprise = $stmtEntreprise->get_result();

        if ($resultEntreprise->num_rows === 0) {
            return ["errors" => ["L'offre de stage spécifiée n'existe pas ou n'a pas d'entreprise associée."]];
        }

        $idEntreprise = $resultEntreprise->fetch_assoc()['id_entreprise_fk'] ?? null;

        if (empty($idEntreprise)) {
            return ["errors" => ["Aucune entreprise associée à cette offre de stage."]];
        }

        // Enregistrer les fichiers sur le serveur
        $uploadDirectory = __DIR__ . '/../../Public/uploads/candidatures/';
        $cvPath = $this->saveFileToServer($cvFile, $uploadDirectory, $errors);
        $motivationPath = $motivationFile ? $this->saveFileToServer($motivationFile, $uploadDirectory, $errors) : null;

        if (!empty($errors)) {
            return ["errors" => $errors];
        }

        // Enregistrer la candidature dans la base de données
        $sql = "INSERT INTO Candidature 
                    (id_etudiant_fk, id_offre_fk, date_candidature, statut_candidature, commentaire, id_entreprise_fk, cv_path, motivation_path)
                VALUES 
                    (?, ?, ?, 'en attente', NULL, ?, ?, ?)"; // Suppression de cv_blob

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Erreur de préparation de la requête SQL : " . $this->conn->error);
            return ["errors" => ["Erreur interne du serveur."]];
        }
        $stmt->bind_param("iissss", $etudiantId, $stageId, $dateCandidature, $idEntreprise, $cvPath, $motivationPath);

        if ($stmt->execute()) {
            return ["success" => "Votre candidature a été envoyée avec succès."];
        } else {
            return ["errors" => ["Erreur lors de l'enregistrement de la candidature."]];
        }
    }

    public function getCandidaturesByEntreprise($entrepriseId) {
        $sql = <<<SQL
            SELECT c.id_candidature, s.prenom, s.nom, c.cv_path, c.motivation_path, o.titre
            FROM Candidature c
            INNER JOIN Stagiaire s ON c.id_etudiant_fk = s.id_stagiaire
            INNER JOIN Offre_Stage o ON c.id_offre_fk = o.`stage-id`
            WHERE o.id_entreprise_fk = ?
SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $entrepriseId);
        $stmt->execute();
        $result = $stmt->get_result();

        $candidatures = [];
        while ($row = $result->fetch_assoc()) {
            $candidatures[] = $row;
        }

        return $candidatures;
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

    // Vérifier si le fichier CV a été téléchargé
    if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
        error_log("Erreur de téléchargement du fichier CV : " . json_encode($_FILES['cv']));
        error_log("Paramètres reçus : " . json_encode($_POST));
        echo json_encode(["errors" => ["Le fichier CV est manquant ou n'a pas été téléchargé correctement."]]);
        exit();
    }

    // Vérifier si le fichier de motivation est optionnel ou correctement téléchargé
    if (isset($_FILES['motivation']) && $_FILES['motivation']['error'] !== UPLOAD_ERR_OK && $_FILES['motivation']['error'] !== UPLOAD_ERR_NO_FILE) {
        error_log("Erreur de téléchargement du fichier de motivation : " . json_encode($_FILES['motivation']));
        echo json_encode(["errors" => ["Le fichier de motivation est manquant ou n'a pas été téléchargé correctement."]]);
        exit();
    }

    // Journaliser les données reçues pour le débogage
    error_log("Données reçues : " . json_encode($_POST));
    error_log("Fichiers reçus : " . json_encode($_FILES));

    $database = new Database();
    $candidatureController = new CandidatureController($database);

    $response = $candidatureController->submitCandidature(
        $etudiantId,
        $stageId,
        $_FILES['cv'],
        $_FILES['motivation'] ?? null // Vérifiez si le fichier de motivation est optionnel
    );

    // Envoyer uniquement la réponse JSON
    echo json_encode($response);
    exit();
}
?>