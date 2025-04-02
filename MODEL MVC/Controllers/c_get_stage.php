<?php
/**
 * Contrôleur pour récupérer les stages.
 * 
 * - Retourne les détails d'un stage spécifique ou la liste de tous les stages.
 * - Utilise la classe `StageController` pour encapsuler la logique.
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

    public function getStageById($stageId) {
        $sql = <<<SQL
            SELECT 
                Offre_Stage.id_offre AS id,
                Offre_Stage.titre AS titre,
                Offre_Stage.description AS description,
                Offre_Stage.date_publi AS date_publi,
                Offre_Stage.date_debut AS date_debut,
                Offre_Stage.duree AS duree,
                Offre_Stage.lieu_stage AS lieu,
                Entreprise.nom_entreprise AS entreprise,
                Entreprise.secteur AS secteur
            FROM 
                Offre_Stage
            LEFT JOIN 
                Entreprise 
            ON 
                Offre_Stage.id_entreprise_fk = Entreprise.id_entreprise
            WHERE 
                Offre_Stage.id_offre = ?
SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $stageId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function getAllStages() {
        $sql = <<<SQL
            SELECT 
                Offre_Stage.id_offre AS id,
                Offre_Stage.titre AS titre,
                Offre_Stage.duree AS duree,
                Offre_Stage.lieu_stage AS lieu,
                Entreprise.nom_entreprise AS entreprise
            FROM 
                Offre_Stage
            LEFT JOIN 
                Entreprise 
            ON 
                Offre_Stage.id_entreprise_fk = Entreprise.id_entreprise
            ORDER BY 
                Offre_Stage.date_publi DESC
SQL;

        $result = $this->conn->query($sql);

        $stages = [];
        while ($row = $result->fetch_assoc()) {
            $stages[] = $row;
        }

        return $stages;
    }
}

// Initialiser le contrôleur
$database = new Database();
$stageController = new StageController($database);

// Vérifier si un `stage_id` est fourni
if (isset($_GET['stage_id']) && ctype_digit($_GET['stage_id'])) {
    $stageId = intval($_GET['stage_id']);
    $stage = $stageController->getStageById($stageId);

    if ($stage) {
        echo json_encode($stage);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Stage non trouvé."]);
    }
} else {
    $stages = $stageController->getAllStages();

    if (!empty($stages)) {
        echo json_encode($stages);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Aucun stage trouvé."]);
    }
}
?>