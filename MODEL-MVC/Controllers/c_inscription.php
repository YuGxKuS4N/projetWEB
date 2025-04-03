<?php
/**
 * Contrôleur pour gérer l'inscription des utilisateurs.
 * 
 * - Vérifie les données du formulaire.
 * - Insère les données dans la base de données en fonction du type d'utilisateur.
 */


require '/Config/config.php';  // Chemin absolu pour le fichier de configuration

class User {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->connect();
    }

    public function register($data) {
        $type = $data['type'] ?? '';
        $prenom = htmlspecialchars(trim($data['prenom'] ?? ''));
        $nom = htmlspecialchars(trim($data['nom'] ?? ''));
        $email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $data['password'] ?? '';
        $confirm_password = $data['confirm_password'] ?? '';

        if (!$email) {
            die("Adresse e-mail invalide !");
        }
        if ($password !== $confirm_password) {
            die("Les mots de passe ne correspondent pas !");
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        switch ($type) {
            case 'candidat':
                return $this->registerCandidat($prenom, $nom, $email, $hashed_password, $data);
            case 'entreprise':
                return $this->registerEntreprise($prenom, $nom, $email, $hashed_password, $data);
            case 'pilote':
                return $this->registerPilote($prenom, $nom, $email, $hashed_password, $data);
            default:
                die("Type d'utilisateur invalide !");
        }
    }

    private function registerCandidat($prenom, $nom, $email, $password, $data) {
        $sql = <<<SQL
            INSERT INTO candidats 
                (prenom, nom, email, password, ecole, lieu_ecole, annee_promo, telephone, date_naissance) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?)
SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssssss", $prenom, $nom, $email, $password, $data['ecole'], $data['lieu_ecole'], $data['annee_promo'], $data['telephone'], $data['date_naissance']);
        return $this->executeStatement($stmt);
    }

    private function registerEntreprise($prenom, $nom, $email, $password, $data) {
        $sql = <<<SQL
            INSERT INTO entreprises 
                (nom_entreprise, prenom, nom, email, password, telephone) 
            VALUES 
                (?, ?, ?, ?, ?, ?)
SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss", $data['nom_entreprise'], $prenom, $nom, $email, $password, $data['telephone']);
        return $this->executeStatement($stmt);
    }

    private function registerPilote($prenom, $nom, $email, $password, $data) {
        $sql = <<<SQL
            INSERT INTO pilotes 
                (prenom, nom, email, password, ecole, lieu_ecole, annee_promo, telephone) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?)
SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssss", $prenom, $nom, $email, $password, $data['ecole'], $data['lieu_ecole'], $data['annee_promo'], $data['telephone']);
        return $this->executeStatement($stmt);
    }

    private function executeStatement($stmt) {
        if ($stmt->execute()) {
            header("Location: ../Views/creation_compte/connexion.php");
            exit();
        } else {
            echo "Erreur lors de l'inscription : " . $stmt->error;
        }
        $stmt->close();
    }
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $database = new Database();
    $user = new User($database);
    $user->register($_POST);
    $database->closeConnection();
}
?>