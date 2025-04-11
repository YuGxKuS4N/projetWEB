<?php
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
            FROM Entreprise 
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
                error_log("Session initialisée : user_id = " . $_SESSION['user_id'] . ", role = " . $_SESSION['role']); // Log pour vérifier
                return ["success" => true, "role" => $user['role']];
            } else {
                return ["success" => false, "error" => "Mot de passe incorrect."];
            }
        } else {
            return ["success" => false, "error" => "Aucun compte trouvé avec cet email."];
        }
    }
}

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