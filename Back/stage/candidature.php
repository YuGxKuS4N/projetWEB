<?php
/**
 * Fichier : candidature.php
 * Description : Ce fichier gère la soumission des candidatures par les étudiants.
 *               Il vérifie les données du formulaire, téléverse les fichiers (CV et lettre de motivation),
 *               enregistre les informations dans la base de données, et retourne une réponse JSON.
 * 
 * Fonctionnalités :
 * - Connexion à la base de données via la classe `Database`.
 * - Validation des données du formulaire (champs obligatoires, email, etc.).
 * - Téléversement sécurisé des fichiers (CV et lettre de motivation).
 * - Gestion des étudiants existants ou création d'un nouvel étudiant.
 * - Enregistrement de la candidature dans la table `Candidature`.
 * 
 * Entrées :
 * - Méthode POST : Données du formulaire de candidature, incluant :
 *   - `stage_id` : ID de l'offre de stage.
 *   - `prenom`, `nom`, `email`, `telephone` : Informations personnelles.
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
 * - Base de données : Tables `Etudiant`, `Candidature`, et `Offre_Stage`.
 * 
 * Auteur : [Votre Nom]
 * Date : [Date de création]
 */

header('Content-Type: application/json');
require '../conf/config.php';

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
    $stageId = intval($_POST['stage_id']);
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $dateCandidature = date('Y-m-d'); // Date actuelle

    // Vérifier les champs obligatoires
    if (empty($prenom) || empty($nom) || empty($email) || empty($telephone)) {
        $errors[] = "Tous les champs sont obligatoires.";
    }

    // Vérifier le format de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse e-mail n'est pas valide.";
    }

    // Vérifier et téléverser le CV
    $cvPath = uploadFile($_FILES['cv'], 'uploads/cv/', $errors);

    // Vérifier et téléverser la lettre de motivation
    $motivationPath = uploadFile($_FILES['motivation'], 'uploads/motivation/', $errors);

    if (empty($errors)) {
        // Vérifier si l'étudiant existe déjà dans la table `Etudiant`
        $stmt = $conn->prepare("SELECT id_etudiant FROM Etudiant WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // L'étudiant existe déjà
            $etudiant = $result->fetch_assoc();
            $etudiantId = $etudiant['id_etudiant'];
        } else {
            // Insérer un nouvel étudiant
            $stmt = $conn->prepare("INSERT INTO Etudiant (nom, prenom, email, date_naissance) VALUES (?, ?, ?, ?)");
            $dateNaissance = '2000-01-01'; // Valeur par défaut si la date de naissance n'est pas fournie
            $stmt->bind_param("ssss", $nom, $prenom, $email, $dateNaissance);
            if ($stmt->execute()) {
                $etudiantId = $stmt->insert_id;
            } else {
                $errors[] = "Erreur lors de l'enregistrement de l'étudiant.";
            }
        }

        // Enregistrer la candidature dans la table `Candidature`
        if (empty($errors)) {
            $stmt = $conn->prepare("
                INSERT INTO Candidature (id_etudiant_fk, id_offre_fk, date_candidature, statut_candidature, commentaire, id_entreprise_fk)
                VALUES (?, ?, ?, 'en attente', NULL, (SELECT id_entreprise_fk FROM Offre_Stage WHERE id_offre = ?))
            ");
            $stmt->bind_param("iisi", $etudiantId, $stageId, $dateCandidature, $stageId);

            if ($stmt->execute()) {
                echo json_encode(["success" => "Votre candidature a été envoyée avec succès."]);
            } else {
                $errors[] = "Erreur lors de l'enregistrement de la candidature.";
            }
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