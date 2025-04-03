<?php
/**
 * Contrôleur pour récupérer les données utilisateur ou les élèves d'un pilote.
 * 
 * - Retourne les informations du profil ou la liste des élèves en fonction du contexte.
 * - Utilise la classe `DataController` pour encapsuler la logique.
 */

require '../Config/config.php'; // Inclusion de la configuration

class DataController {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->connect();
    }

    public function getProfile($type, $userId) {
        $sql = '';
        switch ($type) {
            case 'candidat':
                $sql = <<<SQL
                    SELECT * 
                    FROM Etudiant 
                    WHERE id_etudiant = ?
SQL;
                break;
            case 'pilote':
                $sql = <<<SQL
                    SELECT * 
                    FROM Pilote 
                    WHERE id_pilote = ?
SQL;
                break;
            case 'entreprise':
                $sql = <<<SQL
                    SELECT * 
                    FROM Entreprise 
                    WHERE id_entreprise = ?
SQL;
                break;
            default:
                return ["error" => "Type d'utilisateur invalide."];
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row;
        } else {
            return ["error" => "Utilisateur non trouvé."];
        }
    }

    public function getStudents($userId) {
        $sql = <<<SQL
            SELECT 
                e.nom, 
                e.prenom, 
                COUNT(c.id_candidature) AS nb_candidatures
            FROM 
                Etudiant e
            LEFT JOIN 
                Candidature c 
            ON 
                e.id_etudiant = c.id_etudiant_fk
            WHERE 
                e.id_pilote_fk = ?
            GROUP BY 
                e.id_etudiant
SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }

        return $students;
    }
}

// Vérifier si les paramètres nécessaires sont définis
if (!isset($_GET['type']) || !isset($_GET['user_id'])) {
    echo json_encode(["error" => "Paramètres manquants."]);
    exit();
}

$type = $_GET['type']; // Type d'utilisateur (candidat, pilote, entreprise)
$userId = intval($_GET['user_id']); // ID de l'utilisateur
$context = $_GET['context'] ?? 'profile'; // Contexte : 'profile' ou 'students'

// Initialiser le contrôleur
$database = new Database();
$dataController = new DataController($database);

$response = [];
if ($context === 'profile') {
    $response = $dataController->getProfile($type, $userId);
} elseif ($context === 'students' && $type === 'pilote') {
    $response = $dataController->getStudents($userId);
} else {
    $response = ["error" => "Contexte ou type d'utilisateur invalide."];
}

// Retourner les données au format JSON
echo json_encode($response);
?>