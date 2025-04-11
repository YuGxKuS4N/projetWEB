<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Config/Database.php';

class CandidatureController {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->connect();
    }

    public function getCandidaturesByEntreprise($entrepriseId) {
        $sql = "
            SELECT 
                c.id_candidature,
                s.nom AS stagiaire_nom,
                s.prenom AS stagiaire_prenom,
                os.titre AS offre_titre,
                c.cv_path,
                c.motivation_path
            FROM 
                Candidature c
            JOIN 
                Stagiaire s ON c.id_stagiaire = s.id_stagiaire
            JOIN 
                Offre_Stage os ON c.id_offre_stage = os.`stage-id`
            WHERE 
                os.id_entreprise = ?
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log("Erreur de préparation de la requête : " . $this->conn->error);
            return [];
        }

        $stmt->bind_param("i", $entrepriseId);
        $stmt->execute();
        $result = $stmt->get_result();

        $candidatures = [];
        while ($row = $result->fetch_assoc()) {
            // Corriger les chemins des fichiers pour qu'ils soient accessibles depuis le navigateur
            $row['cv_path'] = str_replace('/var/www/html', '', $row['cv_path']);
            $row['motivation_path'] = str_replace('/var/www/html', '', $row['motivation_path']);
            $candidatures[] = $row;
        }

        return $candidatures;
    }
}