<?php

/**
 * Contrôleur pour gérer la connexion des utilisateurs.
 * 
 * - Vérifie les identifiants de connexion.
 * - Initialise une session pour l'utilisateur connecté.
 * - Utilise la classe `ConnexionController` pour encapsuler la logique.
 */

require_once __DIR__ . '/../Config/config.php'; // Inclusion de la configuration

class ConnexionController {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->connect();
    }

    public function login($email, $password) {
        $sql = <<<SQL
            SELECT id_etudiant AS id, email, password, 'etudiant' AS role 
            FROM Etudiant 
            WHERE email = ?
            UNION
            SELECT id_pilote AS id, email, password, 'pilote' AS role 
            FROM Pilote 
            WHERE email = ?
            UNION
            SELECT id_entreprise AS id, email, password, 'entreprise' AS role 
            FROM Entreprise 
            WHERE email = ?
SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $email, $email, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Journal pour confirmer la connexion réussie
                error_log("Connexion réussie pour l'utilisateur : " . $user['email']);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                return ["success" => true, "role" => $user['role']];
            } else {
                // Journal pour mot de passe incorrect
                error_log("Mot de passe incorrect pour l'utilisateur : " . $email);
                return ["success" => false, "error" => "Mot de passe incorrect."];
            }
        } else {
            // Journal pour email non trouvé
            error_log("Aucun compte trouvé avec l'email : " . $email);
            return ["success" => false, "error" => "Aucun compte trouvé avec cet email."];
        }
    }
}

// Traitement de la requête POST
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $database = new Database();
    $connexionController = new ConnexionController($database);
    $response = $connexionController->login($email, $password);

    if ($response['success']) {
        // Rediriger l'utilisateur vers l'accueil après connexion
        header("Location: ../Vues/accueil.php");
        exit();
    } else {
        // Utilisez une sortie JSON uniquement si nécessaire
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}