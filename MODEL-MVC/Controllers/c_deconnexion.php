<?php
session_start(); // Démarre ou reprend la session

// Supprimer toutes les variables de session
session_unset();

// Détruire la session
session_destroy();

// Rediriger l'utilisateur vers la page d'accueil ou une autre page après la déconnexion
header("Location: /projetWEB/MODEL-MVC/Views/acceuil/acceuil.php");
exit();
?>