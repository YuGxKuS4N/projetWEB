<?php
// filepath: c:\projetWEB\MODEL MVC\Utils\EmailUtils.php

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