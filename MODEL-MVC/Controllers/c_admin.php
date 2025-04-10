<?php
/**
 * Contrôleur pour gérer les actions de l'administrateur.
 * 
 * - Gérer les comptes (afficher, supprimer).
 * - Gérer les stages (afficher, supprimer).
 */


require_once __DIR__ . 'Config/config.php'; // Inclusion de la configuration

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['error' => 'Accès non autorisé.']);
    exit();
}

class AdminController {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->connect();
    }

    public function getAccounts() {
        $sql = "SELECT * FROM Etudiant UNION SELECT * FROM Entreprise UNION SELECT * FROM Pilote";
        $result = $this->conn->query($sql);

        $accounts = [];
        while ($row = $result->fetch_assoc()) {
            $accounts[] = $row;
        }

        return $accounts;
    }

    public function getStages() {
        $sql = "SELECT * FROM Offre_Stage";
        $result = $this->conn->query($sql);

        $stages = [];
        while ($row = $result->fetch_assoc()) {
            $stages[] = $row;
        }

        return $stages;
    }

    public function deleteAccount($id, $role) {
        $table = '';
        if ($role === 'etudiant') {
            $table = 'Etudiant';
        } elseif ($role === 'entreprise') {
            $table = 'Entreprise';
        } elseif ($role === 'pilote') {
            $table = 'Pilote';
        }

        $sql = "DELETE FROM $table WHERE id_$role = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function deleteStage($stageId) {
        $sql = "DELETE FROM Offre_Stage WHERE id_offre = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $stageId);

        return $stmt->execute();
    }
}

// Initialiser le contrôleur
$database = new Database();
$adminController = new AdminController($database);

// Gérer les actions
$action = $_GET['action'] ?? null;

if ($action === 'getAccounts') {
    echo json_encode($adminController->getAccounts());
} elseif ($action === 'getStages') {
    echo json_encode($adminController->getStages());
} elseif ($action === 'deleteAccount') {
    $id = $_POST['id'] ?? null;
    $role = $_POST['role'] ?? null;
    if ($id && $role) {
        $success = $adminController->deleteAccount($id, $role);
        echo json_encode(['success' => $success]);
    } else {
        echo json_encode(['error' => 'Paramètres manquants.']);
    }
} elseif ($action === 'deleteStage') {
    $stageId = $_POST['stageId'] ?? null;
    if ($stageId) {
        $success = $adminController->deleteStage($stageId);
        echo json_encode(['success' => $success]);
    } else {
        echo json_encode(['error' => 'Paramètres manquants.']);
    }
} else {
    echo json_encode(['error' => 'Action invalide.']);
}
?>