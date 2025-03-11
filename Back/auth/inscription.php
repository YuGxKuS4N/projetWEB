<?php
session_start();
require '../config.php'; // Inclusion du fichier de connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action == "inscription") {
        // Récupération et sécurisation des données d'inscription
        $prenom = htmlspecialchars(trim($_POST['prenom']));
        $nom = htmlspecialchars(trim($_POST['nom']));
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
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
    }

    elseif ($action == "connexion") {
        // Récupération et sécurisation des données de connexion
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'];

        if (!$email) {
            die("Format d'e-mail invalide !");
        }

        // Vérification des informations de connexion
        $stmt = $conn->prepare("SELECT id, prenom, nom, password FROM utilisateurs WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $prenom, $nom, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // Connexion réussie, création de la session et du cookie
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
$conn->close();
?>
