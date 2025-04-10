<?php
/**
 * Contrôleur pour récupérer les stages et les options de filtrage.
 */

header('Content-Type: application/json');

// Inclusion de la configuration et de la classe Database
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Config/Database.php'; // Ajout de cette ligne


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
                Offre_Stage.description AS description,
                Offre_Stage.duree AS duree,
                Offre_Stage.lieu AS lieu, -- Correction du champ
                Offre_Stage.date_debut AS date_debut,
                Offre_Stage.secteur_activite AS secteur_activite,
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
        if (!empty($filters['secteur'])) {
            $sql .= " AND Offre_Stage.secteur_activite = ?";
        }

        $sql .= " ORDER BY Offre_Stage.date_publi DESC";

        // Ajout de journaux pour déboguer
        error_log("Requête SQL : $sql");

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Erreur de préparation de la requête : " . $this->conn->error);
        }

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
        if (!empty($filters['secteur'])) {
            $params[] = $filters['secteur'];
            $types .= "s";
        }

        // Ajout de journaux pour déboguer
        error_log("Paramètres : " . json_encode($params));

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
    $stageController = new StageController($database);

    $search = $_GET['search'] ?? '';
    $filters = [
        'lieu' => $_GET['lieu'] ?? '',
        'duree' => $_GET['duree'] ?? '',
        'secteur' => $_GET['secteur'] ?? ''
    ];

    error_log("Paramètres de recherche : " . json_encode($filters)); // Journal pour débogage
    $stages = $stageController->getFilteredStages($search, $filters);

    if (!empty($stages)) {
        error_log("Stages récupérés : " . json_encode($stages)); // Journal des stages récupérés
        echo json_encode($stages);
    } else {
        http_response_code(404);
        error_log("Aucun stage trouvé."); // Journal si aucun stage n'est trouvé
        echo json_encode(["error" => "Aucun stage trouvé."]);
    }
} catch (Exception $e) {
    error_log("Erreur dans c_get_stage.php : " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Erreur interne du serveur."]);
}
?>