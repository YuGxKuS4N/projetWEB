<?php
/**
 * Contrôleur pour gérer les notifications.
 * 
 * - Vérifie les statuts des candidatures pour les étudiants et les entreprises.
 * - Envoie des notifications pop-up et des e-mails en fonction des événements.
 */

require '../Config/config.php'; // Inclusion de la configuration
require '../Utils/EmailUtils.php'; // Inclusion de l'utilitaire pour envoyer des e-mails

class NotificationController {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->connect();
    }

    public function checkStudentNotifications($studentId) {
        $sql = <<<SQL
            SELECT 
                c.id_candidature,
                c.statut_candidature,
                o.titre AS stage_titre,
                e.nom_entreprise AS entreprise_nom
            FROM 
                Candidature c
            INNER JOIN 
                Offre_Stage o ON c.id_offre_fk = o.id_offre
            INNER JOIN 
                Entreprise e ON o.id_entreprise_fk = e.id_entreprise
            WHERE 
                c.id_etudiant_fk = ?
            AND 
                c.notification_envoyee = 0
SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();

        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $notifications[] = [
                "message" => "Votre candidature pour le stage \"{$row['stage_titre']}\" chez \"{$row['entreprise_nom']}\" a été mise à jour avec le statut : {$row['statut_candidature']}."
            ];

            // Envoyer un e-mail à l'étudiant
            $emailUtils = new EmailUtils();
            $emailUtils->send(
                $this->getUserEmail($studentId, 'etudiant'),
                "Mise à jour de votre candidature",
                $notifications[count($notifications) - 1]['message']
            );
        }

        // Marquer les notifications comme envoyées
        $this->markNotificationsAsSent($studentId);

        return $notifications;
    }

    public function checkCompanyNotifications($companyId) {
        $sql = <<<SQL
            SELECT 
                c.id_candidature,
                e.prenom AS etudiant_prenom,
                e.nom AS etudiant_nom,
                o.titre AS stage_titre
            FROM 
                Candidature c
            INNER JOIN 
                Etudiant e ON c.id_etudiant_fk = e.id_etudiant
            INNER JOIN 
                Offre_Stage o ON c.id_offre_fk = o.id_offre
            WHERE 
                o.id_entreprise_fk = ?
            AND 
                c.notification_envoyee = 0
SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $companyId);
        $stmt->execute();
        $result = $stmt->get_result();

        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $notifications[] = [
                "message" => "Un étudiant nommé \"{$row['etudiant_prenom']} {$row['etudiant_nom']}\" a postulé pour le stage \"{$row['stage_titre']}\"."
            ];

            // Envoyer un e-mail à l'entreprise
            $emailUtils = new EmailUtils();
            $emailUtils->send(
                $this->getUserEmail($companyId, 'entreprise'),
                "Nouvelle candidature reçue",
                $notifications[count($notifications) - 1]['message']
            );
        }

        // Marquer les notifications comme envoyées
        $this->markNotificationsAsSentForCompany($companyId);

        return $notifications;
    }

    private function markNotificationsAsSent($studentId) {
        $sql = <<<SQL
            UPDATE 
                Candidature 
            SET 
                notification_envoyee = 1
            WHERE 
                id_etudiant_fk = ?
SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
    }

    private function markNotificationsAsSentForCompany($companyId) {
        $sql = <<<SQL
            UPDATE 
                Candidature 
            SET 
                notification_envoyee = 1
            WHERE 
                id_offre_fk IN (
                    SELECT 
                        id_offre 
                    FROM 
                        Offre_Stage 
                    WHERE 
                        id_entreprise_fk = ?
                )
SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $companyId);
        $stmt->execute();
    }

    private function getUserEmail($userId, $role) {
        $sql = $role === 'etudiant'
            ? "SELECT email FROM Etudiant WHERE id_etudiant = ?"
            : "SELECT email FROM Entreprise WHERE id_entreprise = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row['email'];
        }

        return null;
    }
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    echo json_encode(["error" => "Vous devez être connecté pour accéder aux notifications."]);
    exit();
}

$database = new Database();
$notificationController = new NotificationController($database);

$response = [];
if ($_SESSION['role'] === 'etudiant') {
    $response = $notificationController->checkStudentNotifications($_SESSION['user_id']);
} elseif ($_SESSION['role'] === 'entreprise') {
    $response = $notificationController->checkCompanyNotifications($_SESSION['user_id']);
} else {
    $response = ["error" => "Rôle utilisateur invalide."];
}

echo json_encode($response);
?>