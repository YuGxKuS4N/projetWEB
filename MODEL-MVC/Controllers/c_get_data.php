<?php
/**
 * Contrôleur pour récupérer les données utilisateur ou les élèves d'un pilote.
 * 
 * - Retourne les informations du profil ou la liste des élèves en fonction du contexte.
 * - Utilise la classe `DataController` pour encapsuler la logique.
 */

require __DIR__ . '/../Config/config.php'; // Correction du chemin
require __DIR__ . '/../Config/Database.php'; // Inclure la classe Database avec le chemin relatif correct

class DataController {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    public function getProfile($userType, $userId) {
        $sql = '';
        switch ($userType) {
            case 'stagiaire':
                $sql = <<<SQL
                    SELECT * 
                    FROM Stagiaire 
                    WHERE id_stagiaire = ?
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
        if (!$stmt) {
            return ["error" => "Erreur de préparation de la requête : " . $this->conn->error];
        }

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
        if (!$stmt) {
            return ["error" => "Erreur de préparation de la requête : " . $this->conn->error];
        }

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

// ✅ Vérifier si les paramètres nécessaires sont définis
if (!isset($_GET['user_type']) || !isset($_GET['user_id'])) {
    error_log("Paramètres manquants : " . json_encode($_GET)); // Journal pour le débogage
    echo json_encode(["error" => "Paramètres manquants."]);
    exit();
}

error_log("Paramètres reçus : user_type=" . $_GET['user_type'] . ", user_id=" . $_GET['user_id']); // Journal pour le débogage

$userType = $_GET['user_type']; // ✅ nouveau nom de paramètre
$userId = intval($_GET['user_id']); // ID de l'utilisateur
$context = $_GET['context'] ?? 'profile'; // Contexte : 'profile' ou 'students'

// Initialiser le contrôleur
$dataController = new DataController();

$response = [];
if ($context === 'profile') {
    $response = $dataController->getProfile($userType, $userId);
} elseif ($context === 'students' && $userType === 'pilote') {
    $response = $dataController->getStudents($userId);
} else {
    $response = ["error" => "Contexte ou type d'utilisateur invalide."];
}

// Journal pour le débogage
error_log("Réponse du contrôleur : " . json_encode($response));

// Retourner les données au format JSON
echo json_encode($response);
?>
