<?php
/**
 * Fichier : get_stage.php
 * Description : Ce fichier gère deux cas principaux :
 *               1. Récupération des détails d'un stage spécifique en fonction de son ID.
 *               2. Récupération de la liste de tous les stages disponibles.
 * 
 * Entrées :
 * - `stage_id` (optionnel) : Si fourni dans l'URL (par exemple, `?stage_id=1`), le fichier retourne les détails du stage correspondant.
 * 
 * Sorties :
 * - Si `stage_id` est fourni :
 *   Retourne un objet JSON contenant les détails du stage spécifique.
 * - Si `stage_id` n'est pas fourni :
 *   Retourne un tableau JSON contenant la liste de tous les stages disponibles.
 * 
 * Dépendances :
 * - Fichier de configuration : `../conf/config.php` (pour la connexion à la base de données).
 */

header('Content-Type: application/json');
require '../conf/config.php';

// Classe pour gérer la connexion à la base de données
class Database {
    private $host = "127.0.0.1";
    private $username = "root";
    private $password = "projet213";
    private $dbname = "projetweb";
    private $port = 8080;
    private $conn;

    public function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname, $this->port);

        if ($this->conn->connect_error) {
            http_response_code(500); // Code HTTP 500 pour une erreur serveur
            echo json_encode(["error" => "Connexion échouée : " . $this->conn->connect_error]);
            exit;
        }

        return $this->conn;
    }
}

// Initialiser la connexion à la base de données
$database = new Database();
$conn = $database->connect();

// Vérifier si un `stage_id` est fourni
if (isset($_GET['stage_id']) && ctype_digit($_GET['stage_id'])) { // Validation stricte pour un entier positif
    // Récupérer les détails d'un stage spécifique
    $stageId = intval($_GET['stage_id']);
    $sql = "
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
        FROM Offre_Stage
        LEFT JOIN Entreprise ON Offre_Stage.id_entreprise_fk = Entreprise.id_entreprise
        WHERE Offre_Stage.id_offre = ?
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500); // Code HTTP 500 pour une erreur serveur
        echo json_encode(["error" => "Erreur lors de la préparation de la requête."]);
        exit;
    }

    $stmt->bind_param("i", $stageId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        http_response_code(404); // Code HTTP 404 pour une ressource non trouvée
        echo json_encode(["error" => "Stage non trouvé."]);
    }

    $stmt->close();
} else {
    // Récupérer la liste de tous les stages
    $sql = "
        SELECT 
            Offre_Stage.id_offre AS id,
            Offre_Stage.titre AS titre,
            Offre_Stage.duree AS duree,
            Offre_Stage.lieu_stage AS lieu,
            Entreprise.nom_entreprise AS entreprise
        FROM Offre_Stage
        LEFT JOIN Entreprise ON Offre_Stage.id_entreprise_fk = Entreprise.id_entreprise
        ORDER BY Offre_Stage.date_publi DESC
    ";

    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $stages = [];
            while ($row = $result->fetch_assoc()) {
                $stages[] = $row;
            }
            echo json_encode($stages);
        } else {
            http_response_code(404); // Code HTTP 404 pour une ressource non trouvée
            echo json_encode(["error" => "Aucun stage trouvé."]);
        }
    } else {
        http_response_code(500); // Code HTTP 500 pour une erreur serveur
        echo json_encode(["error" => "Erreur lors de l'exécution de la requête."]);
    }
}

$conn->close();
?>