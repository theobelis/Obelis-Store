<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->mail->SMTPDebug = 0;
        $this->mail->isSMTP();
        $this->mail->Host = HOST_SMTP;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = USER_SMTP;
        $this->mail->Password = PASS_SMTP;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = PUERTO_SMTP;
        $this->mail->CharSet = 'UTF-8';
    }

    public function enviarCorreo($destinatario, $asunto, $mensaje, $nombre = '') {
        try {
            $this->mail->setFrom(USER_SMTP, TITLE);
            $this->mail->addAddress($destinatario, $nombre);
            $this->mail->isHTML(true);
            $this->mail->Subject = $asunto;
            $this->mail->Body = $mensaje;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
