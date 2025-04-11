<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class SessionManager {
    public static function isUserConnected() {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']);
    }

    public static function getConnectedUser() {
        if (self::isUserConnected()) {
            return [
                'user_id' => $_SESSION['user_id'],
                'role' => $_SESSION['role']
            ];
        }
        return null;
    }

    public static function setUserSession($userId, $role, $email) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['role'] = $role;
        $_SESSION['email'] = $email;

        // Log pour vérifier les données de session
        error_log("Session définie : user_id = $userId, role = $role, email = $email");
    }

    public static function setAdditionalSessionData($key, $value) {
        $_SESSION[$key] = $value;

        // Log pour vérifier les données supplémentaires
        error_log("Session supplémentaire définie : $key = $value");
    }

    public static function logout() {
        session_destroy();
        header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php");
        exit();
    }
}