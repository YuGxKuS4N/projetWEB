<?php
/**
 * Contrôleur pour récupérer les stages et les options de filtrage.
 */

header('Content-Type: application/json');

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fichiers de configuration et de base de données
require_once dirname(__DIR__, 2) . '/Config/config.php';
require_once dirname(__DIR__, 2) . '/Config/Database.php';

class StageController {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->connect();

        if (!$this->conn) {
            throw new Exception("Erreur de connexion à la base de données : " . $this->db->connect_error);
        }
    }

    public function getFilterOptions() {
        $options = [
            'lieux' => [],
            'durees' => [],
            'profils' => []
        ];

        try {
            // Récupérer les lieux uniques
            $sqlLieux = "SELECT DISTINCT lieu_stage FROM Offre_Stage";
            $resultLieux = $this->conn->query($sqlLieux);
            if (!$resultLieux) {
                throw new Exception("Erreur SQL lors de la récupération des lieux : " . $this->conn->error);
            }
            while ($row = $resultLieux->fetch_assoc()) {
                $options['lieux'][] = $row['lieu_stage'];
            }

            // Récupérer les durées uniques
            $sqlDurees = "SELECT DISTINCT duree FROM Offre_Stage";
            $resultDurees = $this->conn->query($sqlDurees);
            if (!$resultDurees) {
                throw new Exception("Erreur SQL lors de la récupération des durées : " . $this->conn->error);
            }
            while ($row = $resultDurees->fetch_assoc()) {
                $options['durees'][] = $row['duree'];
            }

            // Récupérer les profils demandés uniques
            $sqlProfils = "SELECT DISTINCT profil_demande FROM Offre_Stage";
            $resultProfils = $this->conn->query($sqlProfils);
            if (!$resultProfils) {
                throw new Exception("Erreur SQL lors de la récupération des profils : " . $this->conn->error);
            }
            while ($row = $resultProfils->fetch_assoc()) {
                $options['profils'][] = $row['profil_demande'];
            }

            return $options;
        } catch (Exception $e) {
            error_log("Erreur dans getFilterOptions : " . $e->getMessage());
            http_response_code(500);
            echo json_encode(["error" => "Erreur interne du serveur."]);
            exit();
        }
    }

    public function getStages($filters) {
        $sql = "SELECT * FROM Offre_Stage WHERE 1=1";
        if (!empty($filters['lieu'])) {
            $sql .= " AND lieu_stage = '" . $this->conn->real_escape_string($filters['lieu']) . "'";
        }
        if (!empty($filters['duree'])) {
            $sql .= " AND duree = '" . $this->conn->real_escape_string($filters['duree']) . "'";
        }
        if (!empty($filters['profil'])) {
            $sql .= " AND profil_demande = '" . $this->conn->real_escape_string($filters['profil']) . "'";
        }

        $result = $this->conn->query($sql);
        if (!$result) {
            throw new Exception("Erreur SQL : " . $this->conn->error);
        }

        $stages = [];
        while ($row = $result->fetch_assoc()) {
            $stages[] = $row;
        }
        return $stages;
    }
}

// Initialiser le contrôleur
try {
    $database = new Database();
    $stageController = new StageController($database);

    if (isset($_GET['action']) && $_GET['action'] === 'getFilters') {
        $options = $stageController->getFilterOptions();
        echo json_encode($options);
        exit();
    }

    if (isset($_GET['search'])) {
        $filters = [
            'lieu' => $_GET['lieu'] ?? '',
            'duree' => $_GET['duree'] ?? '',
            'profil' => $_GET['profil'] ?? ''
        ];
        $stages = $stageController->getStages($filters);
        echo json_encode($stages);
        exit();
    }

    http_response_code(400);
    echo json_encode(["error" => "Action invalide ou manquante."]);
} catch (Exception $e) {
    error_log("Erreur dans c_get_stage.php : " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur interne du serveur."]);
}
?>