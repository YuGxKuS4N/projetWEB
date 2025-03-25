<?php
session_start();
require '../config.php'; // Inclusion du fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération du type d'utilisateur
    $type = isset($_POST['type']) ? $_POST['type'] : '';

    // Récupération et sécurisation des données communes
    $prenom = htmlspecialchars(trim($_POST['prenom'] ?? ''));
    $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation des mots de passe
    if ($password !== $confirm_password) {
        die("Les mots de passe ne correspondent pas !");
    }

    // Hashage sécurisé du mot de passe
    $hashed_password = hash('sha256', $password);

    if ($type === 'candidat') {
        // Champs spécifiques au candidat
        $ecole = htmlspecialchars(trim($_POST['ecole'] ?? ''));
        $lieu_ecole = htmlspecialchars(trim($_POST['lieu_ecole'] ?? ''));
        $annee_promo = htmlspecialchars(trim($_POST['annee_promo'] ?? ''));
        $telephone = htmlspecialchars(trim($_POST['telephone'] ?? ''));
        $date_naissance = htmlspecialchars(trim($_POST['date_naissance'] ?? ''));

        // Insertion dans la base de données
        $stmt = $conn->prepare("INSERT INTO candidats (prenom, nom, email, password, ecole, lieu_ecole, annee_promo, telephone, date_naissance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $prenom, $nom, $email, $hashed_password, $ecole, $lieu_ecole, $annee_promo, $telephone, $date_naissance);
    } elseif ($type === 'entreprise') {
        // Champs spécifiques à l'entreprise
        $nom_entreprise = htmlspecialchars(trim($_POST['nom_entreprise'] ?? ''));
        $telephone = htmlspecialchars(trim($_POST['telephone'] ?? ''));

        // Insertion dans la base de données
        $stmt = $conn->prepare("INSERT INTO entreprises (nom_entreprise, prenom, nom, email, password, telephone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nom_entreprise, $prenom, $nom, $email, $hashed_password, $telephone);
    } elseif ($type === 'pilote') {
        // Champs spécifiques au pilote
        $ecole = htmlspecialchars(trim($_POST['ecole'] ?? ''));
        $lieu_ecole = htmlspecialchars(trim($_POST['lieu_ecole'] ?? ''));
        $annee_promo = htmlspecialchars(trim($_POST['annee_promo'] ?? ''));
        $telephone = htmlspecialchars(trim($_POST['telephone'] ?? ''));

        // Insertion dans la base de données
        $stmt = $conn->prepare("INSERT INTO pilotes (prenom, nom, email, password, ecole, lieu_ecole, annee_promo, telephone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $prenom, $nom, $email, $hashed_password, $ecole, $lieu_ecole, $annee_promo, $telephone);
    } else {
        die("Type d'utilisateur invalide !");
    }

    // Exécution de la requête
    if ($stmt->execute()) {
        header("Location: connexion.html"); // Redirection après succès
        exit();
    } else {
        echo "Erreur lors de l'inscription.";
    }

    $stmt->close();
}

$conn->close();
?>