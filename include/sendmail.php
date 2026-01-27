<?php
require_once __DIR__ . '/../vendor/autoload.php';

session_start();

$_SESSION['last_sent'] = time();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['SMTP_USER'];
    $mail->Password   = $_ENV['SMTP_PASS'];
    $mail->SMTPSecure = 'tls';
    $mail->Port       = $_ENV['SMTP_PORT'];

    $mail->setFrom($_ENV['SMTP_FROM'], 'Notificaciones Aviatraining and Technology web');
    $mail->addAddress($_ENV['SMTP_TO']);
    $mail->isHTML(false);
    $mail->Subject = 'Se ha recibido un nuevo mensaje desde el formulario de contacto desde la pagina web';
    $mail->Body    = "Nombre: {$_POST['form_name']}\nCorreo: {$_POST['form_email']}\nMensaje:\n{$_POST['form_message']}";

    $mail->send();
    header("Location: /index.html?success=1");
    exit;
} catch (Exception $e) {
    header("Location: /index.html?success=0");
    exit;
}
?>