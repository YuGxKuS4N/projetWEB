<?php
session_start();
require '../Config/config.php'; // Inclusion du fichier de configuration

// Classe Database pour gérer la connexion à la base de données
class Database {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die("Échec de la connexion à la base de données : " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function closeConnection() {
        $this->conn->close();
    }
}

// Classe User pour gérer l'inscription
class User {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->getConnection();
    }

    public function register($data) {
        $type = $data['type'] ?? '';
        $prenom = htmlspecialchars(trim($data['prenom'] ?? ''));
        $nom = htmlspecialchars(trim($data['nom'] ?? ''));
        $email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $data['password'] ?? '';
        $confirm_password = $data['confirm_password'] ?? '';

        // Validation des champs
        if (!$email) {
            die("Adresse e-mail invalide !");
        }
        if ($password !== $confirm_password) {
            die("Les mots de passe ne correspondent pas !");
        }

        // Hashage sécurisé du mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Enregistrement en fonction du type d'utilisateur
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
        $stmt = $this->conn->prepare("
            INSERT INTO candidats (prenom, nom, email, password, ecole, lieu_ecole, annee_promo, telephone, date_naissance)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "sssssssss",
            $prenom, $nom, $email, $password,
            htmlspecialchars(trim($data['ecole'] ?? '')),
            htmlspecialchars(trim($data['lieu_ecole'] ?? '')),
            htmlspecialchars(trim($data['annee_promo'] ?? '')),
            htmlspecialchars(trim($data['telephone'] ?? '')),
            htmlspecialchars(trim($data['date_naissance'] ?? ''))
        );
        return $this->executeStatement($stmt);
    }

    private function registerEntreprise($prenom, $nom, $email, $password, $data) {
        $stmt = $this->conn->prepare("
            INSERT INTO entreprises (nom_entreprise, prenom, nom, email, password, telephone)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "ssssss",
            htmlspecialchars(trim($data['nom_entreprise'] ?? '')),
            $prenom, $nom, $email, $password,
            htmlspecialchars(trim($data['telephone'] ?? ''))
        );
        return $this->executeStatement($stmt);
    }

    private function registerPilote($prenom, $nom, $email, $password, $data) {
        $stmt = $this->conn->prepare("
            INSERT INTO pilotes (prenom, nom, email, password, ecole, lieu_ecole, annee_promo, telephone)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "ssssssss",
            $prenom, $nom, $email, $password,
            htmlspecialchars(trim($data['ecole'] ?? '')),
            htmlspecialchars(trim($data['lieu_ecole'] ?? '')),
            htmlspecialchars(trim($data['annee_promo'] ?? '')),
            htmlspecialchars(trim($data['telephone'] ?? ''))
        );
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