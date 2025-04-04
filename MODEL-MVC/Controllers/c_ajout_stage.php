<?php
/**
 * Contrôleur pour gérer l'ajout d'une offre de stage.
 * 
 * - Vérifie si l'utilisateur est connecté et est une entreprise.
 * - Récupère les données du formulaire et les insère dans la base de données.
 * - Utilise la classe `StageController` pour encapsuler la logique.
 */

require_once '/Config/config.php'; // Inclusion de la configuration

class StageController {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->connect();
    }

    public function addStage($data, $idEntreprise) {
        $titre = htmlspecialchars(trim($data['titre']));
        $description = htmlspecialchars(trim($data['description']));
        $secteur = htmlspecialchars(trim($data['secteur']));
        $dateDebut = htmlspecialchars(trim($data['date_debut']));
        $duree = (int) htmlspecialchars(trim($data['duree']));
        $lieuStage = htmlspecialchars(trim($data['lieu_stage']));

        $sql = <<<SQL
            INSERT INTO Offre_Stage 
                (titre, description, secteur, date_publi, date_debut, duree, lieu_stage, id_entreprise_fk)
            VALUES 
                (?, ?, ?, NOW(), ?, ?, ?, ?)
SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssisi", $titre, $description, $secteur, $dateDebut, $duree, $lieuStage, $idEntreprise);

        if ($stmt->execute()) {
            return ["success" => true, "message" => "Offre de stage publiée avec succès."];
        } else {
            return ["success" => false, "message" => "Erreur lors de la publication de l'offre : " . $stmt->error];
        }
    }
}

// Démarrer la session si ce n'est pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté et est une entreprise
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entreprise') {
    $_SESSION['message'] = "Accès non autorisé. Vous devez être connecté en tant qu'entreprise.";
    header("Location: ../Views/ajout_stage/ajout.php");
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_submitted'])) {
    // Récupérer les données du formulaire
    $data = $_POST;
    $idEntreprise = $_SESSION['user_id']; // ID de l'entreprise connectée

    // Ajouter l'offre de stage
    try {
        $database = new Database();
        $stageController = new StageController($database);
        $response = $stageController->addStage($data, $idEntreprise);

        // Stocker le message dans la session et rediriger
        $_SESSION['message'] = $response['message'];
    } catch (Exception $e) {
        $_SESSION['message'] = "Erreur inattendue : " . $e->getMessage();
    }
} else {
    $_SESSION['message'] = "Formulaire non soumis correctement.";
}

header("Location: ../Views/ajout_stage/ajout.php");
exit();
?>