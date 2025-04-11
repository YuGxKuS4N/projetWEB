<?php

require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Config/Database.php';

/**
 * Classe ConnexionController
 * Gère la logique de connexion des utilisateurs.
 */
class ConnexionController {
    private $db;
    private $conn;

    /**
     * Constructeur de la classe.
     * @param Database $database
     */
    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->connect();
    }

    /**
     * Authentifie un utilisateur avec son email et son mot de passe.
     * @param string $email
     * @param string $password
     * @return array
     */
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
                return [
                    "success" => true,
                    "user_id" => $user['id'],
                    "role" => $user['role']
                ];
            } else {
                return ["success" => false, "error" => "Mot de passe incorrect."];
            }
        } else {
            return ["success" => false, "error" => "Aucun compte trouvé avec cet email."];
        }
    }
}