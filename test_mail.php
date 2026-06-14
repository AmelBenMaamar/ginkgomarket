<?php
require_once 'vendor/autoload.php';
require_once 'config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = MAIL_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = MAIL_USER;
    $mail->Password   = MAIL_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = (int)MAIL_PORT;
    $mail->CharSet    = 'UTF-8';
    $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
    $mail->addAddress(MAIL_USER);
    $mail->isHTML(true);
    $mail->Subject = 'Test GinkGoMarket';
    $mail->Body    = '<p>Test email PHPMailer ✅</p>';
    $mail->send();
    echo "Email envoyé avec succès !";
} catch (Exception $e) {
    echo "Erreur : " . $mail->ErrorInfo;
}
