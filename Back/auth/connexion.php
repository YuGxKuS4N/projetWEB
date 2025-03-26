<?php
// filepath: c:\projetWEB\Back\auth\connexion.php
session_start();
require '../config.php'; // Inclusion du fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et sécurisation des données du formulaire
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    if (!$email || !$password) {
        die("Veuillez remplir tous les champs.");
    }

    // Vérification des informations dans la base de données
    $stmt = $conn->prepare("SELECT id, prenom, nom, password FROM utilisateurs WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        // Récupération des données utilisateur
        $stmt->bind_result($id, $prenom, $nom, $hashed_password);
        $stmt->fetch();

        // Vérification du mot de passe
        if (hash('sha256', $password) === $hashed_password) {
            // Connexion réussie : Initialisation de la session
            $_SESSION['user_id'] = $id;
            $_SESSION['prenom'] = $prenom;
            $_SESSION['nom'] = $nom;

            // Redirection vers la page d'accueil ou tableau de bord
            header("Location: ../../index/index.html");
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Aucun compte trouvé avec cet e-mail.";
    }

    $stmt->close();
}

$conn->close();
?>