<?php
session_start();
require_once '../Config/config.php'; // Inclusion du fichier de configuration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $errors = [];

    // Validation des champs
    if (empty($email)) {
        $errors[] = "L'email est requis.";
        
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }

    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    }

    if (empty($errors)) {
        // Connexion à la base de données
        $db = new Database();
        $conn = $db->connect();

        // Rechercher l'utilisateur dans les trois tables
        $stmt = $conn->prepare("
            SELECT id_etudiant AS id, email, password, 'etudiant' AS role FROM Etudiant WHERE email = ?
            UNION
            SELECT id_pilote AS id, email, password, 'pilote' AS role FROM Pilote WHERE email = ?
            UNION
            SELECT id_entreprise AS id, email, password, 'entreprise' AS role FROM Entreprise WHERE email = ?
        ");
        $stmt->bind_param("sss", $email, $email, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Vérifier le mot de passe
            if (password_verify($password, $user['password'])) {
                // Connexion réussie : Initialiser la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role']; // Stocker le rôle (etudiant, pilote, entreprise)

                // Retourner une réponse JSON pour indiquer le succès
                echo json_encode(["success" => true, "role" => $user['role']]);
            } else {
                // Mot de passe incorrect
                echo json_encode(["success" => false, "error" => "Mot de passe incorrect."]);
            }
        } else {
            // Aucun utilisateur trouvé avec cet email
            echo json_encode(["success" => false, "error" => "Aucun compte trouvé avec cet email."]);
        }

        $stmt->close();
    } else {
        // Retourner les erreurs de validation
        echo json_encode(["success" => false, "errors" => $errors]);
    }
}
?>