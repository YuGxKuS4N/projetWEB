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

    public static function logout() {
        session_destroy();
        header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php");
        exit();
    }
}