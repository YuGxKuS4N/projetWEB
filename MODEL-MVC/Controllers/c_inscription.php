<?php

/**
 * Contrôleur pour gérer l'inscription des utilisateurs.
 *
 * - Vérifie les données du formulaire.
 * - Insère les données dans la base de données en fonction du type d'utilisateur.
 */

require_once dirname(__DIR__, 3) . '/projetWEB/MODEL-MVC/Config/config.php'; // Correction du chemin

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion de la configuration et de la classe Database
require_once __DIR__ . '/../Config/config.php';
require_once __DIR__ . '/../Config/Database.php'; // Ajout de cette ligne

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

        if (!$this->validatePassword($password)) {
            die("Le mot de passe doit contenir :
                - Au moins une majuscule,
                - Au moins un caractère spécial,
                - Et au moins 4 chiffres."
            );
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        switch ($type) {
            case 'stagiaire':
                return $this->registerCandidat($prenom, $nom, $email, $hashed_password, $data);
            case 'entreprise':
                return $this->registerEntreprise($prenom, $nom, $email, $hashed_password, $data);
            case 'pilote':
                return $this->registerPilote($prenom, $nom, $email, $hashed_password, $data);
            default:
                die("Type d'utilisateur invalide !");
        }
    }

    private function validatePassword($password) {
        $hasUpperCase = preg_match('/[A-Z]/', $password);
        $hasSpecialChar = preg_match('/[\W]/', $password);
        $hasFourDigits = preg_match('/\d{4,}/', $password);

        return $hasUpperCase && $hasSpecialChar && $hasFourDigits;
    }

    private function registerCandidat($prenom, $nom, $email, $password, $data) {
        $sql = <<<SQL
            INSERT INTO Stagiaire 
                (prenom, nom, email, password, telephone, date_naissance) 
            VALUES 
                (?, ?, ?, ?, ?, ?)
SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss", $prenom, $nom, $email, $password, $data['telephone'], $data['date_naissance']);
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
            INSERT INTO Pilote 
                (prenom, nom, email, password, ecole, lieu_ecole, annee_promo, telephone) 
            VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?)
SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssss", $prenom, $nom, $email, $password, $data['ecole'], $data['lieu_ecole'], 
                                          $data['annee_promo'], $data['telephone']);
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

// Vérifier si le script est exécuté dans un contexte HTTP
if (php_sapi_name() !== 'cli' && isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] === "POST") {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/projetWEB/MODEL-MVC/Config/Database.php';

    $database = new Database();
    $user = new User($database);

    $user->register($_POST);

    $database->disconnect(); // Ferme proprement la connexion
} else {
    echo "Ce script doit être exécuté dans un contexte HTTP avec une requête POST.";
}
?>
