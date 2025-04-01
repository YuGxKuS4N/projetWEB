<?php
session_start();
require '../config.php'; // Inclusion du fichier de connexion

// Classe Database pour gérer la connexion à la base de données
class Database {
    private $conn;

    public function __construct() {
        // Tentative de connexion à la base de données
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die("Échec de la connexion à la base de données : " . $this->conn->connect_error);
        }
    }

    // Retourne l'objet de connexion
    public function getConnection() {
        return $this->conn;
    }

    // Ferme la connexion
    public function closeConnection() {
        $this->conn->close();
    }
}

// Classe User pour gérer l'enregistrement
class User {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->getConnection();
    }

    // Fonction pour enregistrer l'utilisateur en fonction du type
    public function register($data) {
        $type = $data['type'] ?? '';  // Récupère le type d'utilisateur
        $prenom = htmlspecialchars(trim($data['prenom'] ?? ''));
        $nom = htmlspecialchars(trim($data['nom'] ?? ''));
        $email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $data['password'] ?? '';
        $confirm_password = $data['confirm_password'] ?? '';

        // Vérification des mots de passe
        if ($password !== $confirm_password) {
            die("Les mots de passe ne correspondent pas !");
        }

        // Hashage sécurisé
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Enregistrement en fonction du type d'utilisateur
        if ($type === 'candidat') {
            return $this->registerCandidat($prenom, $nom, $email, $hashed_password, $data);
        } elseif ($type === 'entreprise') {
            return $this->registerEntreprise($prenom, $nom, $email, $hashed_password, $data);
        } elseif ($type === 'pilote') {
            return $this->registerPilote($prenom, $nom, $email, $hashed_password, $data);
        } else {
            die("Type d'utilisateur invalide !");
        }
    }

    // Fonction pour enregistrer un candidat
    private function registerCandidat($prenom, $nom, $email, $password, $data) {
        $stmt = $this->conn->prepare("INSERT INTO candidats (prenom, nom, email, password, ecole, lieu_ecole, annee_promo, telephone, date_naissance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
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

    // Fonction pour enregistrer une entreprise
    private function registerEntreprise($prenom, $nom, $email, $password, $data) {
        $stmt = $this->conn->prepare("INSERT INTO entreprises (nom_entreprise, prenom, nom, email, password, telephone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssssss",
            htmlspecialchars(trim($data['nom_entreprise'] ?? '')),
            $prenom, $nom, $email, $password,
            htmlspecialchars(trim($data['telephone'] ?? ''))
        );
        return $this->executeStatement($stmt);
    }

    // Fonction pour enregistrer un pilote
    private function registerPilote($prenom, $nom, $email, $password, $data) {
        $stmt = $this->conn->prepare("INSERT INTO pilotes (prenom, nom, email, password, ecole, lieu_ecole, annee_promo, telephone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
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

    // Fonction pour exécuter l'instruction préparée
    private function executeStatement($stmt) {
        if ($stmt->execute()) {
            header("Location: connexion.html"); // Redirection après succès
            exit();
        } else {
            echo "Erreur lors de l'inscription.";
        }
        $stmt->close();
    }
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $user = new User($database);
    $user->register($_POST);  // Passe les données du formulaire à la fonction register
    $database->closeConnection();
}
?>
