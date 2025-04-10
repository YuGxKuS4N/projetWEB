<?php
// Inclusion du fichier EmailUtils.php avec un chemin absolu
require_once dirname(__DIR__, 2) . '/Utils/EmailUtils.php';  // Chemin absolu depuis la racine du projet

class EmailUtils {
    private $from;
    private $replyTo;

    public function __construct($from = "no-reply@projetweb.com", $replyTo = "no-reply@projetweb.com") {
        $this->from = $from;
        $this->replyTo = $replyTo;
    }

    public function send($to, $subject, $message) {
        $headers = "From: {$this->from}\r\n";
        $headers .= "Reply-To: {$this->replyTo}\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        if (mail($to, $subject, $message, $headers)) {
            return true;
        } else {
            error_log("Erreur lors de l'envoi de l'e-mail à $to avec le sujet $subject.");
            return false;
        }
    }
}
?>
