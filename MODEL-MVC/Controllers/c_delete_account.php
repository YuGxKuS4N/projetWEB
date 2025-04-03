<?php
/**
 * Contrôleur pour gérer la suppression des comptes.
 * 
 * - Supprime le compte de l'utilisateur connecté.
 * - Envoie une notification par e-mail après la suppression.
 */

require_once '/Config/config.php'; // Chemin absolu vers la configuration
require_once '/Utils/EmailUtils.php'; // Chemin absolu vers l'utilitaire pour envoyer des e-mails

class DeleteAccountController {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->connect();
    }

    public function deleteAccount($userId, $role) {
        // Déterminer la table en fonction du rôle
        $table = '';
        if ($role === 'etudiant') {
            $table = 'Etudiant';
        } elseif ($role === 'pilote') {
            $table = 'Pilote';
        } elseif ($role === 'entreprise') {
            $table = 'Entreprise';
        } else {
            return ['success' => false, 'message' => 'Rôle utilisateur invalide.'];
        }

        // Supprimer l'utilisateur de la table correspondante
        $sql = "DELETE FROM $table WHERE id_{$role} = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            // Envoyer un e-mail de confirmation
            $emailUtils = new EmailUtils();
            $emailUtils->send(
                $_SESSION['email'],
                "Suppression de votre compte",
                "Votre compte a été supprimé avec succès. Si vous avez des questions, contactez-nous."
            );

            // Détruire la session
            session_destroy();

            return ['success' => true, 'message' => 'Compte supprimé avec succès.'];
        } else {
            return ['success' => false, 'message' => 'Erreur lors de la suppression du compte.'];
        }
    }
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
    exit();
}

// Initialiser le contrôleur
$database = new Database();
$deleteAccountController = new DeleteAccountController($database);

// Supprimer le compte
$response = $deleteAccountController->deleteAccount($_SESSION['user_id'], $_SESSION['role']);
echo json_encode($response);
?>
