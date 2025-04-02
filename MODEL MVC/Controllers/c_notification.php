<?php
/**
 * Contrôleur pour gérer les notifications.
 * 
 * - Vérifie les statuts des candidatures pour les étudiants et les entreprises.
 * - Envoie des notifications pop-up et des e-mails en fonction des événements.
 * - Utilise la classe `NotificationController` pour encapsuler la logique.
 */

require '../Config/config.php'; // Inclusion de la configuration
require '../Utils/email_utils.php'; // Inclusion d'un utilitaire pour envoyer des e-mails

class NotificationController {
    private $db;
    private $conn;

    public function __construct(Database $database) {
        $this->db = $database;
        $this->conn = $this->db->connect();
    }

    /**
     * Vérifie les notifications pour un étudiant.
     */
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
            $notifications[] = $row;

            // Envoyer un e-mail à l'étudiant
            $this->sendEmail(
                $studentId,
                "Mise à jour de votre candidature",
                "Votre candidature pour le stage \"{$row['stage_titre']}\" chez \"{$row['entreprise_nom']}\" a été mise à jour avec le statut : {$row['statut_candidature']}."
            );
        }

        // Marquer les notifications comme envoyées
        $this->markNotificationsAsSent($studentId);

        return $notifications;
    }

    /**
     * Vérifie les notifications pour une entreprise.
     */
    public function checkCompanyNotifications($companyId) {
        $sql = <<<SQL
            SELECT 
                c.id_candidature,
                c.statut_candidature,
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
            $notifications[] = $row;

            // Envoyer un e-mail à l'entreprise
            $this->sendEmail(
                $companyId,
                "Nouvelle candidature reçue",
                "Un étudiant nommé \"{$row['etudiant_prenom']} {$row['etudiant_nom']}\" a postulé pour le stage \"{$row['stage_titre']}\"."
            );
        }

        // Marquer les notifications comme envoyées
        $this->markNotificationsAsSentForCompany($companyId);

        return $notifications;
    }

    /**
     * Marque les notifications comme envoyées pour un étudiant.
     */
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

    /**
     * Marque les notifications comme envoyées pour une entreprise.
     */
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

    /**
     * Envoie un e-mail à l'utilisateur.
     */
    private function sendEmail($userId, $subject, $message) {
        // Récupérer l'adresse e-mail de l'utilisateur
        $sql = <<<SQL
            SELECT 
                email 
            FROM 
                Etudiant 
            WHERE 
                id_etudiant = ?
            UNION
            SELECT 
                email 
            FROM 
                Entreprise 
            WHERE 
                id_entreprise = ?
SQL;

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $email = $row['email'];
            sendEmail($email, $subject, $message); // Fonction utilitaire pour envoyer un e-mail
        }
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
if ($_SESSION['role'] === 'stagiaire') {
    $response = $notificationController->checkStudentNotifications($_SESSION['user_id']);
} elseif ($_SESSION['role'] === 'entreprise') {
    $response = $notificationController->checkCompanyNotifications($_SESSION['user_id']);
} else {
    $response = ["error" => "Rôle utilisateur invalide."];
}

echo json_encode($response);
?>