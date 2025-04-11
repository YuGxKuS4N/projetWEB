<?php
session_start(); // Démarrage de la session
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Config/Database.php';

class GetDateController {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    public function getUserData($userId, $userType) {
        error_log("Début de getUserData - userId: $userId, userType: $userType"); // Log de début

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
                error_log("Type d'utilisateur invalide : $userType"); // Log d'erreur
                return ["error" => "Type d'utilisateur invalide."];
        }

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Erreur de préparation de la requête : " . $this->conn->error); // Log d'erreur SQL
            return ["error" => "Erreur de préparation de la requête : " . $this->conn->error];
        }

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            error_log("Données utilisateur trouvées : " . json_encode($row)); // Log des données récupérées
            return $row;
        } else {
            error_log("Aucune donnée trouvée pour userId: $userId, userType: $userType"); // Log si aucune donnée
            return ["error" => "Utilisateur non trouvé."];
        }
    }
}

// Vérifier si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    error_log("Utilisateur non connecté."); // Log si l'utilisateur n'est pas connecté
    echo json_encode(["error" => "Utilisateur non connecté."]);
    exit();
}

$userId = $_SESSION['user_id'];
$userType = $_SESSION['role'];

error_log("Session - userId: $userId, userType: $userType"); // Log des valeurs de session

$getDateController = new GetDateController();
$response = $getDateController->getUserData($userId, $userType);

error_log("Réponse envoyée : " . json_encode($response)); // Log de la réponse envoyée

header('Content-Type: application/json');
echo json_encode($response);