<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname(__DIR__, 2) . '/Config/config.php';
require_once dirname(__DIR__, 2) . '/Config/Database.php';

class WishlistController {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->connect();
    }

    public function addToWishlist($idStagiaire, $stageId) {
        $sqlCheck = "SELECT * FROM wishlist WHERE id_stagiaire = ? AND id_stage = ?";
        $stmt = $this->conn->prepare($sqlCheck);
        $stmt->bind_param("ii", $idStagiaire, $stageId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return ['success' => false, 'message' => 'Ce stage est déjà dans votre wishlist.'];
        }

        $sqlInsert = "INSERT INTO wishlist (id_stagiaire, id_stage) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sqlInsert);
        $stmt->bind_param("ii", $idStagiaire, $stageId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Stage ajouté à la wishlist.'];
        } else {
            return ['success' => false, 'message' => 'Erreur lors de l\'ajout à la wishlist.'];
        }
    }

    public function removeFromWishlist($idStagiaire, $stageId) {
        $sqlDelete = "DELETE FROM wishlist WHERE id_stagiaire = ? AND id_stage = ?";
        $stmt = $this->conn->prepare($sqlDelete);
        $stmt->bind_param("ii", $idStagiaire, $stageId);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            return ['success' => true, 'message' => 'Stage retiré de la wishlist.'];
        } else {
            return ['success' => false, 'message' => 'Erreur lors de la suppression du stage de la wishlist.'];
        }
    }
}

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'stagiaire') {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$stageId = $data['stageId'] ?? null;
$action = $data['action'] ?? null;

if (!$stageId || !$action) {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants.']);
    exit();
}

$idStagiaire = $_SESSION['user_id'];
$database = new Database();
$wishlistController = new WishlistController($database);

if ($action === 'add') {
    $response = $wishlistController->addToWishlist($idStagiaire, $stageId);
} elseif ($action === 'remove') {
    $response = $wishlistController->removeFromWishlist($idStagiaire, $stageId);
} else {
    $response = ['success' => false, 'message' => 'Action invalide.'];
}

header('Content-Type: application/json');
echo json_encode($response);