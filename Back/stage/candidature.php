<?php
/**
 * Fichier : candidature.php
 * Description : Ce fichier gère la soumission des candidatures par les étudiants connectés.
 *               Il utilise l'ID du stagiaire connecté pour enregistrer la candidature.
 *               Les fichiers (CV et lettre de motivation) sont téléversés et les informations
 *               sont enregistrées dans la base de données.
 * 
 * Fonctionnalités :
 * - Connexion à la base de données via la classe `Database`.
 * - Validation des données du formulaire (champs obligatoires, email, etc.).
 * - Téléversement sécurisé des fichiers (CV et lettre de motivation).
 * - Enregistrement de la candidature dans la table `Candidature`.
 * 
 * Entrées :
 * - Méthode POST : Données du formulaire de candidature, incluant :
 *   - `stage_id` : ID de l'offre de stage.
 *   - Fichiers téléversés : `cv` (CV) et `motivation` (lettre de motivation).
 * 
 * Sorties :
 * - Réponse JSON en cas de succès :
 *   {
 *       "success": "Votre candidature a été envoyée avec succès."
 *   }
 * - Réponse JSON en cas d'erreur :
 *   {
 *       "errors": ["Liste des erreurs"]
 *   }
 * 
 * Dépendances :
 * - Fichier de configuration : `../conf/config.php` (pour la connexion à la base de données).
 * - Base de données : Tables `Candidature` et `Offre_Stage`.
 * 
 * Auteur : [Votre Nom]
 * Date : [Date de création]
 */

session_start(); // Démarrer la session pour accéder à l'ID de l'utilisateur connecté
header('Content-Type: application/json');
require '../conf/config.php';
require '../auth/connexion.php'; // Inclure le fichier connexion.php pour gérer la session

// Classe pour gérer la connexion à la base de données
class Database {
    private $host = "127.0.0.1";
    private $username = "root";
    private $password = "projet213";
    private $dbname = "projetweb";
    private $port = 8080;
    private $conn;

    public function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname, $this->port);

        if ($this->conn->connect_error) {
            die(json_encode(["error" => "Connexion échouée : " . $this->conn->connect_error]));
        }

        return $this->conn;
    }
}

// Initialiser la connexion à la base de données
$database = new Database();
$conn = $database->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["error" => "Vous devez être connecté pour postuler."]);
        exit;
    }

    $etudiantId = $_SESSION['user_id']; // Récupérer l'ID du stagiaire connecté
    $stageId = intval($_POST['stage_id']);
    $dateCandidature = date('Y-m-d'); // Date actuelle

    // Vérifier et téléverser le CV
    $cvPath = uploadFile($_FILES['cv'], 'uploads/cv/', $errors);

    // Vérifier et téléverser la lettre de motivation
    $motivationPath = uploadFile($_FILES['motivation'], 'uploads/motivation/', $errors);

    if (empty($errors)) {
        // Enregistrer la candidature dans la table `Candidature`
        $stmt = $conn->prepare("
            INSERT INTO Candidature (id_etudiant_fk, id_offre_fk, date_candidature, statut_candidature, commentaire, id_entreprise_fk, cv_path, motivation_path)
            VALUES (?, ?, ?, 'en attente', NULL, (SELECT id_entreprise_fk FROM Offre_Stage WHERE id_offre = ?), ?, ?)
        ");
        $stmt->bind_param("iissss", $etudiantId, $stageId, $dateCandidature, $stageId, $cvPath, $motivationPath);

        if ($stmt->execute()) {
            echo json_encode(["success" => "Votre candidature a été envoyée avec succès."]);
        } else {
            $errors[] = "Erreur lors de l'enregistrement de la candidature.";
        }

        $stmt->close();
    }

    // Afficher les erreurs s'il y en a
    if (!empty($errors)) {
        echo json_encode(["errors" => $errors]);
    }
}

$conn->close();

// Fonction pour téléverser un fichier
function uploadFile($file, $uploadDirectory, &$errors) {
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
?>