<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/c_connexion.php';
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Config/Database.php';

// Vérifier la connexion de l'utilisateur
if (!isUserConnected()) {
    error_log("Utilisateur non connecté.");
    header('Content-Type: application/json');
    echo json_encode(["error" => "Utilisateur non connecté."]);
    exit();
}

$user = getConnectedUser();
$userId = $user['user_id'];
$userType = $user['role'];

class GetDataController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getUserData($userId, $userType) {
        $sql = '';
        switch ($userType) {
            case 'Etudiant': // Anciennement 'stagiaire'
                $sql = "SELECT * FROM Etudiant WHERE id_etudiant = ?";
                break;
            case '¨Pilote':
                $sql = "SELECT * FROM Pilote WHERE id_pilote = ?";
                break;
            case 'Entreprise':
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
            foreach ($row as $key => $value) {
                if (is_null($value)) {
                    $row[$key] = "Non défini";
                }
            }
            return $row;
        } else {
            return ["error" => "Utilisateur non trouvé."];
        }
    }
}

$controller = new GetDataController();
$response = $controller->getUserData($userId, $userType);

header('Content-Type: application/json');
echo json_encode($response);
?>