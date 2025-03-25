<?php
/**
 * Script d'inscription et de connexion utilisateur.
 *
 * Ce fichier gère deux actions principales :
 * 1. Inscription : Récupère les données du formulaire d'inscription, les valide, 
 *    et les insère dans la base de données si elles sont valides.
 * 2. Connexion : Vérifie les informations de connexion fournies par l'utilisateur 
 *    et initialise une session si elles sont correctes.
 *
 * Sécurité :
 * - Les entrées utilisateur sont nettoyées pour éviter les attaques XSS.
 * - Les mots de passe sont hashés avec SHA-256 avant d'être stockés.
 * - Les requêtes SQL utilisent des requêtes préparées pour éviter les injections SQL.
 */

session_start();
require '../config.php'; // Inclusion du fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération de l'action (inscription ou connexion)
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action == "inscription") {
        // Récupération et sécurisation des données d'inscription
        $prenom = htmlspecialchars(trim($_POST['prenom'])); // Nettoyage du prénom
        $nom = htmlspecialchars(trim($_POST['nom'])); // Nettoyage du nom
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL); // Validation de l'email
        $password = $_POST['password']; // Mot de passe brut
        $confirm_password = $_POST['confirm_password']; // Confirmation du mot de passe
        $date_naissance = htmlspecialchars($_POST['date_naissance']); // Nettoyage de la date de naissance
        $telephone = htmlspecialchars($_POST['telephone']); // Nettoyage du numéro de téléphone
        $annee_etude = htmlspecialchars($_POST['annee_etude']); // Nettoyage de l'année d'étude
        $domaine_stage = htmlspecialchars($_POST['domaine_stage']); // Nettoyage du domaine de stage
        $localite = htmlspecialchars($_POST['localite']); // Nettoyage de la localité

        // Validation des champs obligatoires
        if (!$email) {
            die("Format d'e-mail invalide !");
        }

        // Vérification que les mots de passe correspondent
        if ($password !== $confirm_password) {
            die("Les mots de passe ne correspondent pas !");
        }

        // Hashage sécurisé du mot de passe avec SHA-256
        $hashed_password = hash('sha256', $password);

        // Vérification de l'unicité de l'e-mail
        $stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            die("Cet e-mail est déjà utilisé !");
        }
        $stmt->close();

        // Insertion des données dans la base de données
        $stmt = $conn->prepare("INSERT INTO utilisateurs (prenom, nom, email, password, date_naissance, telephone, annee_etude, domaine_stage, localite) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $prenom, $nom, $email, $hashed_password, $date_naissance, $telephone, $annee_etude, $domaine_stage, $localite);

        if ($stmt->execute()) {
            // Redirection vers la page de connexion après une inscription réussie
            header("Location: connexion.html");
            exit();
        } else {
            echo "Erreur lors de l'inscription.";
        }
        $stmt->close();
    }

    elseif ($action == "connexion") {
        // Récupération et sécurisation des données de connexion
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL); // Validation de l'email
        $password = $_POST['password']; // Mot de passe brut

        if (!$email) {
            die("Format d'e-mail invalide !");
        }

        // Vérification des informations de connexion
        $stmt = $conn->prepare("SELECT id, prenom, nom, password FROM utilisateurs WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            // Récupération des données utilisateur
            $stmt->bind_result($id, $prenom, $nom, $hashed_password);
            $stmt->fetch();

            // Vérification du mot de passe avec SHA-256
            if (hash('sha256', $password) === $hashed_password) {
                // Connexion réussie : Initialisation de la session et du cookie
                $_SESSION['user_id'] = $id;
                $_SESSION['prenom'] = $prenom;
                $_SESSION['nom'] = $nom;
                setcookie("user", json_encode(["id" => $id, "prenom" => $prenom, "nom" => $nom]), time() + (86400 * 30), "/");
                header("Location: index.html"); // Redirection vers la page d'accueil
                exit();
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Aucun compte trouvé avec cet e-mail.";
        }
        $stmt->close();
    }
}

// Fermeture de la connexion à la base de données
$conn->close();
?>