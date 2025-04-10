<?php
session_start();
error_log("Déconnexion appelée. Session actuelle : " . json_encode($_SESSION));
session_unset();
session_destroy();

// Vérifier si un paramètre de redirection est défini
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'acceuil';

// Rediriger vers la page spécifiée
if ($redirect === 'connexion') {
    header("Location: /projetWEB/MODEL-MVC/Views/creation_compte/connexion.php");
} else {
    header("Location: /projetWEB/MODEL-MVC/Views/acceuil/acceuil.php");
}
exit();
?>