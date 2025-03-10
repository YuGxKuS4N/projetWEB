<?php
require 'config.php'; // Inclusion du fichier de connexion

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash du mot de passe
    $domaine_stage = htmlspecialchars($_POST['domaine_stage']);
    $localite = htmlspecialchars($_POST['localite']);

    if (!$email) {
        die("Format d'e-mail invalide !");
    }

    // Vérification de l'unicité de l'e-mail
    $stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        die("Cet e-mail est déjà utilisé !");
    }

    $stmt->close();

    // Insertion dans la base
    $stmt = $conn->prepare("INSERT INTO utilisateurs (prenom, nom, email, password, domaine_stage, localite) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $prenom, $nom, $email, $password, $domaine_stage, $localite);

    if ($stmt->execute()) {
        header("Location: connexion.html"); // Redirection après inscription
        exit();
    } else {
        echo "Erreur lors de l'inscription.";
    }

    $stmt->close();
    $conn->close();
}
?>
