<?php
/**
 * Contrôleur pour récupérer les stages et les options de filtrage.
 */

header('Content-Type: application/json');
require '../Config/config.php'; // Inclusion de la configuration

class StageController {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->connect();
    }

    public function getFilteredStages($search = '', $filters = []) {
        $sql = <<<SQL
            SELECT 
                Offre_Stage.id_offre AS id,
                Offre_Stage.titre AS titre,
                Offre_Stage.duree AS duree,
                Offre_Stage.lieu_stage AS lieu,
                Offre_Stage.profil_demande AS profil,
                Entreprise.nom_entreprise AS entreprise
            FROM 
                Offre_Stage
            LEFT JOIN 
                Entreprise 
            ON 
                Offre_Stage.id_entreprise_fk = Entreprise.id_entreprise
            WHERE 
                (Offre_Stage.titre LIKE ? OR Entreprise.nom_entreprise LIKE ?)
SQL;

        // Ajout des filtres dynamiques
        if (!empty($filters['lieu'])) {
            $sql .= " AND Offre_Stage.lieu_stage = ?";
        }
        if (!empty($filters['duree'])) {
            $sql .= " AND Offre_Stage.duree = ?";
        }
        if (!empty($filters['profil'])) {
            $sql .= " AND Offre_Stage.profil_demande = ?";
        }

        $sql .= " ORDER BY Offre_Stage.date_publi DESC";

        $stmt = $this->conn->prepare($sql);

        // Préparer les paramètres dynamiques
        $searchParam = '%' . $search . '%';
        $params = [$searchParam, $searchParam];
        $types = "ss";

        if (!empty($filters['lieu'])) {
            $params[] = $filters['lieu'];
            $types .= "s";
        }
        if (!empty($filters['duree'])) {
            $params[] = $filters['duree'];
            $types .= "i";
        }
        if (!empty($filters['profil'])) {
            $params[] = $filters['profil'];
            $types .= "s";
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

    public function getFilterOptions() {
        $options = [
            'lieux' => [],
            'durees' => [],
            'profils' => []
        ];

        // Récupérer les lieux uniques
        $sqlLieux = "SELECT DISTINCT lieu_stage FROM Offre_Stage";
        $resultLieux = $this->conn->query($sqlLieux);
        while ($row = $resultLieux->fetch_assoc()) {
            $options['lieux'][] = $row['lieu_stage'];
        }

        // Récupérer les durées uniques
        $sqlDurees = "SELECT DISTINCT duree FROM Offre_Stage";
        $resultDurees = $this->conn->query($sqlDurees);
        while ($row = $resultDurees->fetch_assoc()) {
            $options['durees'][] = $row['duree'];
        }

        // Récupérer les profils demandés uniques
        $sqlProfils = "SELECT DISTINCT profil_demande FROM Offre_Stage";
        $resultProfils = $this->conn->query($sqlProfils);
        while ($row = $resultProfils->fetch_assoc()) {
            $options['profils'][] = $row['profil_demande'];
        }

        return $options;
    }
}

// Initialiser le contrôleur
$database = new Database();
$stageController = new StageController($database);

// Vérifier le type de requête
if (isset($_GET['action']) && $_GET['action'] === 'getFilters') {
    $options = $stageController->getFilterOptions();
    echo json_encode($options);
    exit();
}

$search = $_GET['search'] ?? '';
$filters = [
    'lieu' => $_GET['lieu'] ?? '',
    'duree' => $_GET['duree'] ?? '',
    'profil' => $_GET['profil'] ?? ''
];

$stages = $stageController->getFilteredStages($search, $filters);

if (!empty($stages)) {
    echo json_encode($stages);
} else {
    http_response_code(404);
    echo json_encode(["error" => "Aucun stage trouvé."]);
}
?>