<?php

namespace App\Config;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    protected PHPMailer $mail;

    public function __construct(bool $debug = false)
    {
        $this->mail = new PHPMailer(true);

        // --- Debug SMTP ---
        $this->mail->SMTPDebug = $debug ? 2 : 0;
        $this->mail->Debugoutput = function ($str, $level) {
            error_log("SMTP Debug level $level: $str");
        };

        $this->setupSMTP();
    }

    protected function setupSMTP(): void
    {
        $mail = $this->mail;

        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'] ?? getenv('MAIL_HOST') ?? 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USERNAME'] ?? getenv('MAIL_USERNAME') ?? '';
        $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? getenv('MAIL_PASSWORD') ?? '';
        $mail->Port       = (int)($_ENV['MAIL_PORT'] ?? getenv('MAIL_PORT') ?? 587);

        // Gmail exige TLS
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        // Options SSL
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => true,
                'verify_peer_name'  => true,
                'allow_self_signed' => false,
            ],
        ];

        // Expéditeur par défaut
        $mail->setFrom(
            $_ENV['MAIL_FROM'] ?? getenv('MAIL_FROM') ?? 'solidev.dev@gmail.com',
            $_ENV['MAIL_FROM_NAME'] ?? getenv('MAIL_FROM_NAME') ?? 'SoliDev'
        );
    }

    public function sendContactMail(array $data): array
    {
        try {
            $mail = $this->mail;

            $mail->clearAddresses();
            $mail->addAddress(
                $_ENV['MAIL_TO'] ?? getenv('MAIL_TO') ?? 'solidev.dev@gmail.com'
            );

            $mail->isHTML(true);
            $mail->Subject = $data['subject'] ?? 'Sujet non défini';
            $mail->Body =
                "<p><strong>Nom:</strong> {$data['name']}</p>" .
                "<p><strong>Prénom:</strong> {$data['firstName']}</p>" .
                "<p><strong>Email:</strong> {$data['email']}</p>" .
                "<p><strong>Téléphone:</strong> {$data['phone']}</p>" .
                "<p><strong>Message:</strong><br>" . nl2br($data['message']) . "</p>";

            $mail->send();

            return ['success' => true, 'message' => 'Message envoyé avec succès !'];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => "Erreur SMTP : {$mail->ErrorInfo} ({$e->getMessage()})"
            ];
        }
    }

    public function sendMail(string $to, string $subject, string $body, bool $isHtml = true): array
    {
        try {
            $mail = $this->mail;

            $mail->clearAddresses();
            $mail->addAddress($to);

            $mail->isHTML($isHtml);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();

            return ['success' => true, 'message' => 'Email envoyé avec succès.'];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => "Erreur SMTP : {$mail->ErrorInfo} ({$e->getMessage()})"
            ];
        }
    }
}
