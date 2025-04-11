<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Config/Database.php';

class FiltreStageController {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->connect();
    }

    public function getFilters() {
        $filters = [
            'lieux' => [],
            'durees' => [],
            'profils' => []
        ];

        // Récupérer les lieux
        $result = $this->conn->query("SELECT DISTINCT lieu FROM Offre_Stage");
        while ($row = $result->fetch_assoc()) {
            $filters['lieux'][] = $row['lieu'];
        }

        // Récupérer les durées
        $result = $this->conn->query("SELECT DISTINCT duree FROM Offre_Stage");
        while ($row = $result->fetch_assoc()) {
            $filters['durees'][] = $row['duree'];
        }

        // Récupérer les profils demandés
        $result = $this->conn->query("SELECT DISTINCT secteur_activite FROM Offre_Stage");
        while ($row = $result->fetch_assoc()) {
            $filters['profils'][] = $row['secteur_activite'];
        }

        return $filters;
    }

    public function getStages($search = '', $filters = []) {
        $sql = <<<SQL
            SELECT 
                id_offre AS id,
                titre,
                description,
                duree,
                lieu,
                date_debut,
                secteur_activite
            FROM Offre_Stage
            WHERE (titre LIKE ? OR secteur_activite LIKE ?)
SQL;

        $params = [];
        $types = "ss";
        $searchParam = '%' . $search . '%';
        $params[] = $searchParam;
        $params[] = $searchParam;

        // Ajouter les filtres dynamiques
        if (!empty($filters['lieu'])) {
            $sql .= " AND lieu = ?";
            $params[] = $filters['lieu'];
            $types .= "s";
        }
        if (!empty($filters['duree'])) {
            $sql .= " AND duree = ?";
            $params[] = $filters['duree'];
            $types .= "i";
        }
        if (!empty($filters['profil'])) {
            $sql .= " AND secteur_activite = ?";
            $params[] = $filters['profil'];
            $types .= "s";
        }

        $sql .= " ORDER BY date_debut ASC";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Erreur de préparation de la requête : " . $this->conn->error);
        }

        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

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
    $controller = new FiltreStageController($database);

    if (isset($_GET['action']) && $_GET['action'] === 'getFilters') {
        echo json_encode($controller->getFilters());
    } else {
        $search = $_GET['search'] ?? '';
        $filters = [
            'lieu' => $_GET['lieu'] ?? '',
            'duree' => $_GET['duree'] ?? '',
            'profil' => $_GET['profil'] ?? ''
        ];
        echo json_encode($controller->getStages($search, $filters));
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>