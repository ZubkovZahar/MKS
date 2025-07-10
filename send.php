<?php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = strip_tags($_POST["name"]);
    $email = $_POST["email"];
    $message = strip_tags($_POST["message"]);

    $mail = new PHPMailer(true);

    try {
        // SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = $_ENV['SMTP_SECURE'];
        $mail->Port = $_ENV['SMTP_PORT'];

        // Кодировка
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        // От кого
        $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
        $mail->addReplyTo($email, $name);

        // Кому
        $mail->addAddress($_ENV['MAIL_TO']);

        // Контент
        $mail->isHTML(false);
        $mail->Subject = 'Новое сообщение с сайта МКС';
        $mail->Body = "Имя: $name\nEmail: $email\n\nСообщение:\n$message";

        $mail->send();
        http_response_code(200);
        echo "OK";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Ошибка отправки: {$mail->ErrorInfo}";
    }
} else {
    http_response_code(403);
    echo "Недопустимый метод.";
}
