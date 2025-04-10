<?php

/**
 * Contrôleur pour gérer la connexion des utilisateurs.
 * 
 * - Vérifie les identifiants de connexion.
 * - Initialise une session pour l'utilisateur connecté.
 * - Utilise la classe `ConnexionController` pour encapsuler la logique.
 */

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion de la configuration et de la classe Database
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Config/Database.php'; // Ajout de cette ligne

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

        // Corriger le nombre de variables passées à bind_param
        $stmt->bind_param("sss", $email, $email, $email); // Trois paramètres pour trois `?`
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['last_activity'] = time(); // Ajoutez un horodatage pour suivre l'activité
                $_SESSION['session_start_time'] = time(); // Ajoutez un horodatage pour la durée totale de la session
                return ["success" => true, "role" => $user['role']];
            } else {
                return ["success" => false, "error" => "Mot de passe incorrect."];
            }
        } else {
            return ["success" => false, "error" => "Aucun compte trouvé avec cet email."];
        }
    }
}

// Traitement de la requête POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("Début du traitement de la connexion"); // Journal de débogage
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $database = new Database();
    $connexionController = new ConnexionController($database);
    $response = $connexionController->login($email, $password);

    if ($response['success']) {
        error_log("Connexion réussie pour l'utilisateur : $email"); // Journal de débogage
        error_log("Session après connexion : " . json_encode($_SESSION)); // Journal pour vérifier la session
        // Rediriger l'utilisateur vers l'accueil après connexion
        header("Location: /projetWEB/MODEL-MVC/Views/acceuil/acceuil.php");
        exit();
    } else {
        error_log("Échec de la connexion : " . $response['error']); // Journal de débogage
        // Rediriger vers la page de connexion avec un message d'erreur
        header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php?error=" . urlencode($response['error']));
        exit();
    }
}

error_log("Fin du script c_connexion.php"); // Journal de débogage
?>