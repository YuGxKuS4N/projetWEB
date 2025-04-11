<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/**
 * Contrôleur pour gérer la connexion des utilisateurs.
 * 
 * - Vérifie les identifiants de connexion.
 * - Initialise une session pour l'utilisateur connecté.
 * - Fournit des méthodes pour vérifier la connexion et récupérer les informations utilisateur.
 */

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion de la configuration et de la classe Database
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Config/Database.php';

class ConnexionController {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->connect();
    }

    public function login($email, $password) {
        $sql = <<<SQL
            SELECT id_stagiaire AS id, email, password, 'stagiaire' AS role 
            FROM Stagiaire 
            WHERE email = ?
            UNION
            SELECT id_pilote AS id, email, password, 'pilote' AS role 
            FROM Pilote 
            WHERE email = ?
            UNION
            SELECT id_entreprise AS id, email, password, 'entreprise' AS role 
            FROM entreprises 
            WHERE email = ?
SQL;

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Erreur de préparation de la requête : " . $this->conn->error);
            return ["success" => false, "error" => "Erreur interne. Veuillez réessayer plus tard."];
        }

        $stmt->bind_param("sss", $email, $email, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Ajouter annee_promo pour les pilotes
                if ($user['role'] === 'pilote') {
                    $stmt = $this->conn->prepare("SELECT annee_promo FROM Pilote WHERE id_pilote = ?");
                    $stmt->bind_param("i", $user['id']);
                    $stmt->execute();
                    $resultPromo = $stmt->get_result();
                    if ($promoRow = $resultPromo->fetch_assoc()) {
                        $_SESSION['annee_promo'] = $promoRow['annee_promo'];
                    }
                }

                return ["success" => true, "role" => $user['role']];
            } else {
                return ["success" => false, "error" => "Mot de passe incorrect."];
            }
        } else {
            return ["success" => false, "error" => "Aucun compte trouvé avec cet email."];
        }
    }
}

// Méthodes globales pour vérifier la connexion et récupérer les informations utilisateur

/**
 * Vérifie si un utilisateur est connecté.
 * @return bool
 */
function isUserConnected() {
    return isset($_SESSION['user_id']) && isset($_SESSION['role']);
}

/**
 * Récupère les informations de l'utilisateur connecté.
 * @return array|null
 */
function getConnectedUser() {
    if (isUserConnected()) {
        return [
            'user_id' => $_SESSION['user_id'],
            'role' => $_SESSION['role']
        ];
    }
    return null;
}

// Traitement de la requête POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $database = new Database();
    $connexionController = new ConnexionController($database);
    $response = $connexionController->login($email, $password);

    if ($response['success']) {
        header("Location: /projetWEB/MODEL-MVC/Views/acceuil/acceuil.php");
        exit();
    } else {
        header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php?error=" . urlencode($response['error']));
        exit();
    }
}
?>