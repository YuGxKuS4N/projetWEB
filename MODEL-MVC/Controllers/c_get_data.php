<?php
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Config/Database.php';
require_once __DIR__ . '/c_connexion.php'; // Inclure le contrôleur de connexion pour récupérer l'utilisateur connecté

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
        if (!$stmt) {
            return ["error" => "Erreur de préparation de la requête : " . $this->conn->error];
        }

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

// Vérifier si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    echo json_encode(["error" => "Utilisateur non connecté."]);
    exit();
}

$userId = $_SESSION['user_id'];
$userType = $_SESSION['role'];

// Initialiser le contrôleur
$getDateController = new GetDateController();
$response = $getDateController->getUserData($userId, $userType);

// Retourner les données au format JSON
header('Content-Type: application/json');
echo json_encode($response);