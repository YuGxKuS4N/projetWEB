<?php
require_once __DIR__ . '/c_connexion.php';
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Config/Database.php';

// Vérifiez si l'utilisateur est connecté via la session

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    error_log("Utilisateur non connecté.");
    header('Content-Type: application/json');
    echo json_encode(["error" => "Utilisateur non connecté."]);
    exit();
}
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si la session est déjà active avant d'appeler session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
/* 
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    // Rediriger vers la page de connexion si non connecté
    header("Location: ../creation_compte/connexion.php");
    exit();
} */ 

// Récupérez les informations de l'utilisateur connecté
$userId = $_SESSION['user_id'];
$userType = $_SESSION['role'];

class GetDateController {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    public function getUserData($userId, $userType) {
        $sql = '';
        switch ($userType) {
            case 'stagiaire':
                $sql = "SELECT * FROM Stagiaire WHERE id_stagiaire = ?";
                break;
            case 'pilote':
                $sql = "SELECT * FROM Pilote WHERE id_pilote = ?";
                break;
            case 'entreprise':
                $sql = "SELECT * FROM Entreprise WHERE id_entreprise = ?";
                break;
            default:
                return ["error" => "Type d'utilisateur invalide."];
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row;
        } else {
            return ["error" => "Utilisateur non trouvé."];
        }
    }
}

$getDateController = new GetDateController();
$response = $getDateController->getUserData($userId, $userType);

header('Content-Type: application/json');
echo json_encode($response);