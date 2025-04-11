<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/c_connexion.php'; // Inclusion du contrôleur de connexion

/**
 * Classe SessionManager
 * Gère les sessions utilisateur et utilise ConnexionController pour récupérer les informations utilisateur.
 */
class SessionManager {
    /**
     * Vérifie si un utilisateur est connecté.
     * @return bool
     */
    public static function isUserConnected() {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']);
    }

    /**
     * Récupère les informations de l'utilisateur connecté.
     * @return array|null
     */
    public static function getConnectedUser() {
        if (self::isUserConnected()) {
            return [
                'user_id' => $_SESSION['user_id'],
                'role' => $_SESSION['role']
            ];
        }
        return null;
    }

    /**
     * Définit les variables de session pour un utilisateur.
     * @param string $email
     * @param string $password
     * @return bool
     */
    public static function loginUser($email, $password) {
        $database = new Database();
        $connexionController = new ConnexionController($database);
        $response = $connexionController->login($email, $password);

        if ($response['success']) {
            $_SESSION['user_id'] = $response['user_id'];
            $_SESSION['role'] = $response['role'];
            $_SESSION['email'] = $email;
            return true;
        }
        return false;
    }

    /**
     * Déconnecte l'utilisateur et détruit la session.
     */
    public static function logout() {
        session_destroy();
        header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php");
        exit();
    }
}